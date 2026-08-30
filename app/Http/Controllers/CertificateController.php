<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Jobs\ProcessCertificateImportJob;
use App\Services\CertificateSearchService;
use App\Services\DashboardService;
use App\Services\ActivityLogService;
use App\Services\PermissionService;
use App\Exports\CertificateExport;
use App\Imports\CertificateImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Certificate Verification System (CVS) - Inspection
| TUV Austria Bureau of Inspection & Certification
|--------------------------------------------------------------------------
*/

class CertificateController extends Controller
{
    private $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    public function search(Request $request)
    {
        if ($request->search == null) {
            return view('/verify-certificate');
        }

        $certificate = Certificate::where('certificate_number', '=', $request->search)
            ->where('status', 'Approved')
            ->paginate(1);

        return view('verify-certificate', ['certificates' => $certificate]);
    }

    public function addCredentials(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $email = $credentials['email'];

        $existing = User::where('email', $email)->first();

        if ($existing && !$existing->isActive()) {
            return redirect('/admin')->with('error', 'Your account has been deactivated. Contact an administrator.');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if ($user->mustChangePassword()) {
                return redirect()->route('account.password.edit')
                    ->with('warning', 'You must set a new password before continuing.');
            }

            return redirect('/dashboard')->with('success', 'Thank You for authorizing. Please proceed.');
        }

        $this->activityLog->record(
            'auth.failed',
            'auth',
            $existing?->id,
            'Failed login attempt for ' . $email . '.',
            ['email' => $email]
        );

        return redirect('/admin')->with('error', 'You entered the wrong credentials');
    }

    public function getDashboard(DashboardService $dashboardService)
    {
        return view('dashboard', $dashboardService->data());
    }

    public function indexCertificates()
    {
        $certificates = Certificate::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(100);

        return view('certificates.index', compact('certificates'));
    }

    public function getDeletedCertificates()
    {
        $certificates = Certificate::onlyTrashed()->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(100);

        return view('deleted-certificates', compact('certificates'));
    }

    public function getPendingCertificates(Request $request)
    {
        $assignment = $request->query('assignment');
        $query = $this->pendingCertificatesQuery($assignment);

        $certificates = $query
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(100)
            ->withQueryString();

        return view('pending-certificates', compact('certificates', 'assignment'));
    }

    public function addCertificate()
    {
        $currentYear = date('Y');
        $currentMonthDay = date('md');
        $users = User::all();

        return view('add-certificate', compact('currentYear', 'currentMonthDay', 'users'));
    }

    public function createCertificate(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|unique:inspection_certificates',
            'inspector' => 'required',
            'client_name' => 'required',
            'inspection_type' => 'required',
            'inspection_location' => 'required',
            'equipment_name' => 'required',
            'inspection_date' => 'required',
            'review_by' => 'required',
            'approval_by' => 'required',
        ]);

        $review_by_user = User::where('name', $request->review_by)->first();
        $review_by_user_id = $review_by_user ? $review_by_user->id : null;

        $approval_by_user = User::where('name', $request->approval_by)->first();
        $approval_by_user_id = $approval_by_user ? $approval_by_user->id : null;

        $certificate = new Certificate();
        $certificate->certificate_number = $request->certificate_number;
        $certificate->inspector = $request->inspector;
        $certificate->client_name = $request->client_name;
        $certificate->inspection_type = $request->inspection_type;
        $certificate->inspection_location = $request->inspection_location;
        $certificate->equipment_name = $request->equipment_name;
        $certificate->equipment_brand = $request->equipment_brand;
        $certificate->equipment_serial_chassis = $request->equipment_serial_chassis;
        $certificate->equipment_rated_capacity = $request->equipment_rated_capacity;
        $certificate->equipment_swl = $request->equipment_swl;
        $certificate->inspection_date = $request->inspection_date;
        $certificate->validity_date = $request->validity_date;
        $certificate->inspection_remarks = $request->inspection_remarks;
        $certificate->inspection_internal_notes = $request->inspection_internal_notes;
        $certificate->status = 'Pending Review';
        $certificate->created_by = Auth::user()->name;
        $certificate->created_by_id = Auth::user()->id;
        $certificate->review_by = $request->review_by;
        $certificate->review_by_id = $review_by_user_id;
        $certificate->approval_by = $request->approval_by;
        $certificate->approval_by_id = $approval_by_user_id;
        $certificate->updated_by = Auth::user()->name;
        $certificate->updated_by_id = Auth::user()->id;
        $certificate->updated_at = Carbon::now();
        $certificate->save();

        $this->activityLog->record(
            'certificate.created',
            'certificate',
            $certificate->id,
            'Certificate ' . $certificate->certificate_number . ' was created.',
            ['status' => $certificate->status]
        );

        return redirect('/view-certificate/' . $certificate->id);
    }

    public function viewCertificate($id)
    {
        $certificate = Certificate::withTrashed()->find($id);

        return view('view-certificate', compact('certificate'));
    }

    public function editCertificate($id)
    {
        if (!app(PermissionService::class)->canMutate()) {
            abort(403, 'You do not have permission to edit certificates.');
        }

        $certificate = Certificate::findOrFail($id);
        $users = User::all();

        return view('edit-certificate', compact('certificate', 'users'));
    }

    public function updateCertificate(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|unique:inspection_certificates,certificate_number,' . $request->id,
            'inspector' => 'required',
            'client_name' => 'required',
            'inspection_type' => 'required',
            'inspection_location' => 'required',
            'equipment_name' => 'required',
            'inspection_date' => 'required',
            'review_by' => 'required',
            'approval_by' => 'required',
        ]);

        $review_by_user = User::where('name', $request->review_by)->first();
        $review_by_user_id = $review_by_user ? $review_by_user->id : null;

        $approval_by_user = User::where('name', $request->approval_by)->first();
        $approval_by_user_id = $approval_by_user ? $approval_by_user->id : null;

        $certificate = Certificate::findOrFail($request->id);
        $certificate->certificate_number = $request->certificate_number;
        $certificate->inspector = $request->inspector;
        $certificate->client_name = $request->client_name;
        $certificate->inspection_type = $request->inspection_type;
        $certificate->inspection_location = $request->inspection_location;
        $certificate->equipment_name = $request->equipment_name;
        $certificate->equipment_brand = $request->equipment_brand;
        $certificate->equipment_serial_chassis = $request->equipment_serial_chassis;
        $certificate->equipment_rated_capacity = $request->equipment_rated_capacity;
        $certificate->equipment_swl = $request->equipment_swl;
        $certificate->inspection_date = $request->inspection_date;
        $certificate->validity_date = $request->validity_date;
        $certificate->inspection_remarks = $request->inspection_remarks;
        $certificate->inspection_internal_notes = $request->inspection_internal_notes;
        $certificate->status = 'Pending Review';
        $certificate->review_by = $request->review_by;
        $certificate->review_by_id = $review_by_user_id;
        $certificate->reviewed_at = null;
        $certificate->approval_by = $request->approval_by;
        $certificate->approval_by_id = $approval_by_user_id;
        $certificate->approved_at = null;
        $certificate->updated_by = Auth::user()->name;
        $certificate->updated_by_id = Auth::user()->id;
        $certificate->updated_at = Carbon::now();
        $certificate->save();

        $this->activityLog->record(
            'certificate.updated',
            'certificate',
            $certificate->id,
            'Certificate ' . $certificate->certificate_number . ' was updated and returned for review.',
            ['status' => $certificate->status]
        );

        return redirect('/view-certificate/' . $certificate->id);
    }

    public function reviewCertificate($id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return back()->with('error', 'Certificate not found.');
        }

        if (Auth::id() != $certificate->review_by_id) {
            return back()->with('error', 'Unauthorized: You are not assigned to review this certificate.');
        }

        $certificate->status = 'Pending Approval';
        $certificate->reviewed_at = Carbon::now();
        $certificate->updated_by = Auth::user()->name;
        $certificate->updated_by_id = Auth::id();
        $certificate->updated_at = Carbon::now();
        $certificate->save();

        $this->activityLog->record(
            'certificate.reviewed',
            'certificate',
            $certificate->id,
            'Certificate ' . $certificate->certificate_number . ' was reviewed.'
        );

        return redirect('/view-certificate/' . $certificate->id)
            ->with('success', 'Certificate marked as Reviewed.');
    }

    public function approveCertificate($id)
    {
        $certificate = Certificate::find($id);

        if (!$certificate) {
            return back()->with('error', 'Certificate not found.');
        }

        if (Auth::id() != $certificate->approval_by_id) {
            return back()->with('error', 'Unauthorized: You are not assigned to approve this certificate.');
        }

        if ($certificate->status !== 'Pending Approval') {
            return back()->with('error', 'Certificate must be reviewed before approval.');
        }

        $certificate->status = 'Approved';
        $certificate->approved_at = Carbon::now();
        $certificate->updated_by = Auth::user()->name;
        $certificate->updated_by_id = Auth::id();
        $certificate->updated_at = Carbon::now();
        $certificate->save();

        $this->activityLog->record(
            'certificate.approved',
            'certificate',
            $certificate->id,
            'Certificate ' . $certificate->certificate_number . ' was approved.'
        );

        return back()->with('success', 'Certificate approved successfully.');
    }

    public function bulkReview()
    {
        $user = Auth::user();

        $updated = Certificate::where('status', 'Pending Review')
            ->where('review_by_id', $user->id)
            ->update([
                'status' => 'Pending Approval',
                'reviewed_at' => Carbon::now(),
                'updated_by' => $user->name,
                'updated_by_id' => $user->id,
                'updated_at' => Carbon::now(),
            ]);

        $this->activityLog->record(
            'certificate.bulk_reviewed',
            'certificate',
            null,
            $updated . ' certificate(s) were bulk reviewed.',
            ['count' => $updated]
        );

        return back()->with('success', $updated . ' certificate(s) marked as Reviewed.');
    }

    public function bulkApprove()
    {
        $user = Auth::user();

        $updated = Certificate::where('status', 'Pending Approval')
            ->where('approval_by_id', $user->id)
            ->update([
                'status' => 'Approved',
                'approved_at' => Carbon::now(),
                'updated_by' => $user->name,
                'updated_by_id' => $user->id,
                'updated_at' => Carbon::now(),
            ]);

        $this->activityLog->record(
            'certificate.bulk_approved',
            'certificate',
            null,
            $updated . ' certificate(s) were bulk approved.',
            ['count' => $updated]
        );

        return back()->with('success', $updated . ' certificate(s) marked as Approved.');
    }

    public function bulkReviewSelected(Request $request)
    {
        $ids = $this->validatedSelectedCertificateIds($request);
        $user = Auth::user();

        $eligibleIds = Certificate::whereIn('id', $ids)
            ->assignedForReview($user->id)
            ->pluck('id')
            ->all();

        $updated = Certificate::whereIn('id', $eligibleIds)->update([
            'status' => 'Pending Approval',
            'reviewed_at' => Carbon::now(),
            'updated_by' => $user->name,
            'updated_by_id' => $user->id,
            'updated_at' => Carbon::now(),
        ]);
        $skipped = count($ids) - $updated;

        $this->activityLog->record(
            'certificate.selected_bulk_reviewed',
            'certificate',
            null,
            $updated . ' selected certificate(s) were bulk reviewed.',
            [
                'selected_ids' => $ids,
                'updated_ids' => $eligibleIds,
                'updated_count' => $updated,
                'skipped_count' => $skipped,
            ]
        );

        return back()
            ->with('success', $updated . ' certificate(s) reviewed; ' . $skipped . ' skipped.')
            ->with('bulk_action_completed', true);
    }

    public function bulkApproveSelected(Request $request)
    {
        $ids = $this->validatedSelectedCertificateIds($request);
        $user = Auth::user();

        $eligibleIds = Certificate::whereIn('id', $ids)
            ->assignedForApproval($user->id)
            ->pluck('id')
            ->all();

        $updated = Certificate::whereIn('id', $eligibleIds)->update([
            'status' => 'Approved',
            'approved_at' => Carbon::now(),
            'updated_by' => $user->name,
            'updated_by_id' => $user->id,
            'updated_at' => Carbon::now(),
        ]);
        $skipped = count($ids) - $updated;

        $this->activityLog->record(
            'certificate.selected_bulk_approved',
            'certificate',
            null,
            $updated . ' selected certificate(s) were bulk approved.',
            [
                'selected_ids' => $ids,
                'updated_ids' => $eligibleIds,
                'updated_count' => $updated,
                'skipped_count' => $skipped,
            ]
        );

        return back()
            ->with('success', $updated . ' certificate(s) approved; ' . $skipped . ' skipped.')
            ->with('bulk_action_completed', true);
    }

    public function bulkDeleteSelected(Request $request)
    {
        $ids = $this->validatedSelectedCertificateIds($request);
        $user = Auth::user();

        $deletedIds = DB::transaction(function () use ($ids, $user) {
            $certificates = Certificate::whereIn('id', $ids)
                ->lockForUpdate()
                ->get();
            $deletedIds = [];

            foreach ($certificates as $certificate) {
                $this->softDeleteCertificate($certificate, $user);
                $deletedIds[] = $certificate->id;
            }

            return $deletedIds;
        });
        $skipped = count($ids) - count($deletedIds);

        $this->activityLog->record(
            'certificate.selected_bulk_deleted',
            'certificate',
            null,
            count($deletedIds) . ' selected certificate(s) were deleted.',
            [
                'selected_ids' => $ids,
                'deleted_ids' => $deletedIds,
                'deleted_count' => count($deletedIds),
                'skipped_count' => $skipped,
            ]
        );

        return back()
            ->with('success', count($deletedIds) . ' certificate(s) deleted; ' . $skipped . ' skipped.')
            ->with('bulk_action_completed', true);
    }

    public function deleteCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        $this->softDeleteCertificate($certificate, Auth::user());

        $this->activityLog->record(
            'certificate.deleted',
            'certificate',
            $certificate->id,
            'Certificate ' . $certificate->certificate_number . ' was deleted.'
        );

        return back()->with('success', 'Certificate details have been deleted successfully');
    }

    private function validatedSelectedCertificateIds(Request $request): array
    {
        $validated = $request->validate([
            'certificate_ids' => 'required|array|min:1|max:500',
            'certificate_ids.*' => 'required|integer|distinct|exists:inspection_certificates,id',
        ]);

        return array_map('intval', $validated['certificate_ids']);
    }

    private function softDeleteCertificate(Certificate $certificate, User $user): void
    {
        $certificate->certificate_number .= ' (Deleted)';
        $certificate->status = 'Deleted';
        $certificate->deleted_by = $user->name;
        $certificate->deleted_by_id = $user->id;
        $certificate->reviewed_at = null;
        $certificate->approved_at = null;
        $certificate->updated_by = $user->name;
        $certificate->updated_by_id = $user->id;
        $certificate->updated_at = Carbon::now();
        $certificate->save();
        $certificate->delete();
    }

    public function uploadPdf(Request $request, $id)
    {
        $request->validate([
            'certificate_pdf' => 'required|mimes:pdf|max:30720',
        ]);

        $certificate = Certificate::findOrFail($id);
        $user = Auth::user();
        $isAuthorized = (
            $user->id == $certificate->review_by_id ||
            $user->id == $certificate->approval_by_id ||
            $user->id == $certificate->created_by_id
        );

        if (!$isAuthorized) {
            return back()->with('error', 'You are not authorized to upload this certificate.');
        }

        $destinationPath = public_path('Certificate PDFs');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $pdfFile = $request->file('certificate_pdf');
        $timestamp = Carbon::now()->format('YmdHi');
        $safeClient = preg_replace('/[^A-Za-z0-9\-_. ]+/', '', $certificate->client_name);
        $fileName = 'TUVAT Insp Cert - ' . $safeClient . ' ' . $timestamp . '.' . $pdfFile->getClientOriginalExtension();

        $pdfFile->move($destinationPath, $fileName);

        $certificate->certificate_pdf = $fileName;
        $certificate->pdf_uploaded_by = $user->name;
        $certificate->pdf_uploaded_by_id = $user->id;
        $certificate->pdf_uploaded_at = now();
        $certificate->updated_by = $user->name;
        $certificate->updated_by_id = $user->id;
        $certificate->updated_at = Carbon::now();
        $certificate->save();

        $this->activityLog->record(
            'certificate.pdf_uploaded',
            'certificate',
            $certificate->id,
            'A PDF was uploaded for certificate ' . $certificate->certificate_number . '.'
        );

        return back()->with('success', 'Certificate PDF uploaded successfully.');
    }

    public function downloadPdf($id)
    {
        $certificate = Certificate::findOrFail($id);
        $filePath = public_path('Certificate PDFs/' . $certificate->certificate_pdf);

        if (!file_exists($filePath)) {
            return back()->with('error', 'PDF file not found.');
        }

        return response()->download($filePath, $certificate->certificate_pdf);
    }

    public function viewPdf($id)
    {
        $certificate = Certificate::findOrFail($id);
        $filePath = public_path('Certificate PDFs/' . $certificate->certificate_pdf);

        if (!file_exists($filePath)) {
            abort(404, 'PDF not found.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $certificate->certificate_pdf . '"',
        ]);
    }

    public function publicPdf($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->status !== 'Approved' || empty($certificate->certificate_pdf)) {
            abort(404, 'PDF not found.');
        }

        $filePath = public_path('Certificate PDFs/' . $certificate->certificate_pdf);

        if (!file_exists($filePath)) {
            abort(404, 'PDF not found.');
        }

        $disposition = request()->boolean('download') ? 'attachment' : 'inline';

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $disposition . '; filename="' . $certificate->certificate_pdf . '"',
        ]);
    }

    public function liveSearch(Request $request, CertificateSearchService $searchService)
    {
        $perPage = 100;
        $userInput = (string) ($request->input('userInput') ?? '');

        $result = $searchService->paginate(Certificate::query(), $userInput, $perPage);

        return response()->json(['data' => $result]);
    }

    public function liveSearchDeleted(Request $request, CertificateSearchService $searchService)
    {
        $perPage = 100;
        $userInput = (string) ($request->input('userInput') ?? '');

        $result = $searchService->paginate(Certificate::onlyTrashed(), $userInput, $perPage);

        return response()->json(['data' => $result]);
    }

    public function liveSearchPending(Request $request, CertificateSearchService $searchService)
    {
        $perPage = 100;
        $userInput = (string) ($request->input('userInput') ?? '');
        $assignment = $request->input('assignment');

        $query = $this->pendingCertificatesQuery($assignment);
        $result = $searchService->paginate($query, $userInput, $perPage);

        return response()->json(['data' => $result]);
    }

    private function pendingCertificatesQuery(?string $assignment)
    {
        $userId = Auth::id();

        if ($assignment === 'review' && $userId) {
            return Certificate::assignedForReview($userId);
        }

        if ($assignment === 'approval' && $userId) {
            return Certificate::assignedForApproval($userId);
        }

        if ($assignment === 'mine' && $userId) {
            return Certificate::assignedToUser($userId);
        }

        return Certificate::where(function ($query) {
            $query->whereIn('status', ['Pending Review', 'Pending'])
                ->orWhereIn('status', ['Pending Approval', 'Reviewed']);
        });
    }

    public function importExportView()
    {
        return view('imports-exports');
    }

    public function export()
    {
        $today = Carbon::now()->format('d-m-Y');
        $fileName = 'TUV Austria BIC Inspection Certificate DB on ' . $today . '.xlsx';

        $this->activityLog->record(
            'export.completed',
            'export',
            null,
            'Certificate data was exported.',
            ['file_name' => $fileName]
        );

        return Excel::download(new CertificateExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ]);

        $originalName = $request->file('file')->getClientOriginalName();

        if (config('queue.default') !== 'sync') {
            $storedPath = $request->file('file')->store('imports');

            ProcessCertificateImportJob::dispatch(
                $storedPath,
                $originalName,
                Auth::id(),
                Auth::user()->name
            );

            return back()->with(
                'success',
                'Certificate import has been queued. Check the activity log when processing completes.'
            );
        }

        try {
            DB::transaction(function () use ($request) {
                Excel::import(
                    new CertificateImport,
                    $request->file('file')
                );
            });

            $this->activityLog->record(
                'import.completed',
                'import',
                null,
                'Certificate data was imported.',
                ['file_name' => $request->file('file')->getClientOriginalName()]
            );

            return back()->with('success', 'Certificate data imported successfully.');
        } catch (\Throwable $e) {
            Log::error('Certificate import failed.', [
                'user_id' => Auth::id(),
                'file_name' => $request->file('file')
                    ? $request->file('file')->getClientOriginalName()
                    : null,
                'error' => $e->getMessage(),
            ]);

            $this->activityLog->record(
                'import.failed',
                'import',
                null,
                'A certificate import failed.',
                [
                    'file_name' => $request->file('file')
                        ? $request->file('file')->getClientOriginalName()
                        : null,
                ]
            );

            $errorMessage = $e->getMessage();

            if (
                strpos($errorMessage, 'SQLSTATE') !== false ||
                strpos($errorMessage, 'Integrity constraint') !== false
            ) {
                $errorMessage = 'The spreadsheet contains duplicate or invalid certificate data.';
            }

            return back()->with('import_error', 'Import failed: ' . $errorMessage);
        }
    }
}
