<?php

namespace App\Http\Controllers;
use App\Models\Certificate;
use App\Exports\CertificateExport;
use App\Imports\CertificateImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class CertificateController extends Controller
{


    ///Unauthenticated user functions
    public function search(Request $request)
    {
        if ($request->search == null) {
            return view('/verify-certificate');
        }
        $certificate = Certificate::where('certificate_number','=',($request->search))->paginate(1);
        return view('verify-certificate',['certificates'=>$certificate]);
    }

    ///Authentication functions
    public function addCredentials(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect('/dashboard')->with('success', 'Thank You for authorizing. Please proceed.');
        }
        else{
            return redirect('/admin')->with('error', 'You entered the wrong credentials');
        }

    }

    public function logout()
    {
        if (Auth::check())
        {
            Auth::logout();
            return redirect('/admin');
        }

        return redirect('/admin');;
    }
    
    
    ////Admin functions
    public function getDashboard()
    {
        if (Auth::check())  
        {
            $certificates = Certificate::orderBy('inspection_date','DESC')->paginate(100); ///Sorted by inspection date
            return view('dashboard',compact('certificates'));
        }

        return redirect('/admin');
    }

    public function getDeletedCertificates()
    {
        if (Auth::check())
        {
            $certificates = Certificate::onlyTrashed()->orderBy('inspection_date','DESC')->paginate(100);
            return view('dashboard',compact('certificates'));
        }

        return redirect('/admin');
    }
    
    public function addCertificate()
    {
        if (Auth::check())
        {
            $currentYear = date('Y');       ///Pass the current year as YYYY to the view file to populate certificate number 
            $currentMonthDay = date('md');  ///Pass the current year as MMDD to the view file to populate certificate number
            return view('add-certificate', compact('currentYear', 'currentMonthDay'));
        }
        else
        {
            return redirect('/admin');
        }
    }

    public function createCertificate(Request $request)
    {
        if (Auth::check())
        {
            $validate = $request->validate([
                'certificate_number' => 'required|unique:certificates', ///check if certificate is unique from "Certificates" table
                'inspector' => 'required',
                'client_name' => 'required',
                'inspection_type' => 'required',
                'inspection_location' => 'required',
                'equipment_name' => 'required',
                'inspection_date' => 'required',
            ]);
            
            $certificate = new certificate();
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
            $certificate->created_by = Auth::user()->name;
            $certificate->save();
            return redirect('/view-certificate/' . $certificate->id);
        }
        return redirect ('/admin');
    }

    public function viewCertificate($id)
    {
        if (Auth::check())
        {
            $certificate = Certificate::withTrashed()->find($id);   ///Ensure deleted certificate info can also be viewed by using withTrashed method.
            return view('view-certificate',compact('certificate'));
        }
        return redirect ('/admin');
    }

    public function editCertificate($id)
    {
        if (Auth::check())
        {
            $certificate = Certificate::find($id);
            return view('edit-certificate',compact('certificate'));
        }
        return redirect ('/admin');
    }

    public function updateCertificate(Request $request)
    {
        if (Auth::check())
        {
            $validate = $request->validate([
                'certificate_number' => 'required|unique:certificates', ///check if certificate is unique from "Certificates" table
                'inspector' => 'required',
                'client_name' => 'required',
                'inspection_type' => 'required',
                'inspection_location' => 'required',
                'equipment_name' => 'required',
                'inspection_date' => 'required',
            ]);
            $certificate = Certificate::find($request->id);
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
            $certificate->updated_by = Auth::user()->name;
            $certificate->save();
            return redirect('/view-certificate/' . $certificate->id);
        }
        return redirect ('/admin');
    }


    public function deleteCertificate($id)
    {
        if (Auth::check())
        {
            $certificate = Certificate::find($id);
            $certificate_number = $certificate->certificate_number . " (Deleted)";
            $deleted_by = Auth::user()->name;
            DB::table('inspection_certificates')->where('id', $id)->update(array('certificate_number' => $certificate_number)); ///Concat "(Deleted)" with cert number to prevent duplicate certificate number error. 
            DB::table('inspection_certificates')->where('id', $id)->update(array('deleted_by' => $deleted_by));    ///Add user's name to deleted by column.
            Certificate::where('id',$id)->delete();
            return back()->with('Certificate_Deleted','Certificate details has been deleted successfully');
        }
        return redirect ('/admin');
    }

    public function adminSearch(Request $request)       ///Not requied as Live Search is used instead
    {
        if (Auth::check())
        {
            $certificates = Certificate::where('certificate_number', 'LIKE', '%' . ($request->search) . '%')
            ->orWhere('inspector','LIKE','%'.($request->search).'%')
            ->orWhere('client_name','LIKE','%'.($request->search).'%')      ///search using % and LIKE to find words in query
            ->orWhere('inspection_type','LIKE','%'.($request->search).'%')
            ->orWhere('inspection_location','LIKE','%'.($request->search).'%')
            ->orWhere('equipment_name','LIKE','%'.($request->search).'%')
            ->orWhere('equipment_brand','LIKE','%'.($request->search).'%')
            ->orWhere('equipment_serial_chassis','LIKE','%'.($request->search).'%')
            ->orWhere('equipment_rated_capacity','LIKE','%'.($request->search).'%')
            ->orWhere('equipment_swl','LIKE','%'.($request->search).'%')
            ->orWhere('inspection_date','LIKE','%'.($request->search).'%')
            ->orWhere('validity_date','LIKE','%'.($request->search).'%')
            /* ->orWhere('inspection_remarks','LIKE','%'.($request->search).'%')
            ->orWhere('inspection_internal_notes','LIKE','%'.($request->search).'%') */
            ->paginate(100); 
            return view('dashboard',compact('certificates'));
        }
        return redirect ('/admin');
    }

    ///Live-Search in Dashboard
    public function liveSearch(Request $request)
    {
        if (Auth::check()) {
            $perPage = 100; // Number of certificates per page
            $userInput = $request->input('userInput', '');
    
            if (empty($userInput)) {
                // If the search input is empty, return all certificates ordered by certificate_number descending with pagination
                $result = Certificate::orderBy('certificate_number', 'desc')->paginate($perPage);
            } else {
                $result = Certificate::where('certificate_number', 'LIKE', '%' . $userInput . '%')
                    ->orWhere('inspector','LIKE','%'. $userInput .'%')    
                    ->orWhere('client_name','LIKE','%'. $userInput .'%')      ///search using % and LIKE to find words in query
                    ->orWhere('inspection_type','LIKE','%'. $userInput .'%')
                    ->orWhere('inspection_location','LIKE','%'. $userInput .'%')
                    ->orWhere('equipment_name','LIKE','%'. $userInput .'%')
                    ->orWhere('equipment_brand','LIKE','%'. $userInput .'%')
                    ->orWhere('equipment_serial_chassis','LIKE','%'. $userInput .'%')
                    ->orWhere('equipment_rated_capacity','LIKE','%'. $userInput .'%')
                    ->orWhere('equipment_swl','LIKE','%'. $userInput .'%')
                    ->orWhere('inspection_date','LIKE','%'. $userInput .'%')
                    ->orWhere('validity_date','LIKE','%'. $userInput .'%')
                    ->orWhere('inspector','LIKE','%'. $userInput .'%')
                    /* ->orWhere('inspection_remarks','LIKE','%'. $userInput .'%')
                    ->orWhere('inspection_internal_notes','LIKE','%'. $userInput .'%') */
                    ->orderBy('certificate_number', 'desc')
                    ->paginate($perPage); // Ensure the result is paginated
            }
    
            return response()->json(['data' => $result]);
        } else {
            return redirect('/admin');
        }
    }
    

    public function importExportView()
    {
        if (Auth::check())
        {
            return view('imports-exports');
        }
       return redirect('/admin');
    }

    public function export() 
    {
        if (Auth::check())
        {
            $today = Carbon::now()->format('d-m-Y');   ///get current date
            $fileName = 'TUV Austria BIC Certificate DB on '.$today.'.xlsx';
            return Excel::download(new CertificateExport, $fileName);
        }
        return redirect('/admin');
    }

    public function import()
    {
        if (Auth::check())
        {
        Excel::import(new CertificateImport,request()->file('file'));
        return redirect ('/dashboard');
        }
        return redirect ('/admin');
    }

}

    // public function generateQRCode($id)     ///function not used anymore as goQR api is implemented.
    // {
    //     if (Auth::check())
    //     {
    //         $certificate = Certificate::find($id);
    //         return view('qrcode', compact('certificate'));  ///send certificate data to view page
    //     }
    //     return redirect('/admin');
    // }
