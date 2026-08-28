@extends('layouts.admin')

@section('title', 'Certificate Details')

@push('styles')
<style>
    .certificate-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div>
        <h1>Certificate Details</h1>
        <p>{{ $certificate->certificate_number }} — {{ $certificate->client_name }}</p>
    </div>
    <div class="certificate-actions">
        <a href="{{ route('certificates.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>

        @if($certificate->status !== 'Deleted')
            <a href="{{ route('certificate.edit', $certificate->id) }}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
            </a>

            @if($certificate->certificate_pdf)
                <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i> Download PDF
                </a>
            @endif

            @if(Auth::user()->id == $certificate->review_by_id && $certificate->status == 'Pending Review')
                <form action="{{ route('certificate.review', $certificate->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm" data-confirm="Mark this certificate as Reviewed?">
                        <i class="fa-solid fa-thumbs-up me-1"></i> Mark as Reviewed
                    </button>
                </form>
            @endif

            @if(Auth::user()->id == $certificate->approval_by_id && $certificate->status == 'Pending Approval')
                <form action="{{ route('certificate.approve', $certificate->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" data-confirm="Mark this certificate as Approved?">
                        <i class="fa-solid fa-check me-1"></i> Mark as Approved
                    </button>
                </form>
            @endif

            <form action="{{ route('certificate.delete', $certificate->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete this certificate?">
                    <i class="fa-solid fa-trash me-1"></i> Delete
                </button>
            </form>
        @endif
    </div>
</div>

<section class="admin-card">
    <div class="admin-card-header"><h2>Record Summary</h2></div>
    <div class="admin-card-body">
        @if(
            Auth::user()->id == $certificate->created_by_id ||
            Auth::user()->id == $certificate->review_by_id ||
            Auth::user()->id == $certificate->approval_by_id
        )
            <form
                action="{{ route('certificate.uploadPdf', $certificate->id) }}"
                method="POST"
                enctype="multipart/form-data"
                class="mb-3"
            >
                @csrf
                <div class="input-group" style="max-width: 600px;">
                    <input type="file" name="certificate_pdf" class="form-control" accept="application/pdf" required>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa-solid fa-upload me-1"></i>
                        {{ $certificate->certificate_pdf ? 'Re-upload Certificate' : 'Upload Certificate' }}
                    </button>
                </div>
            </form>
        @endif

        @if($certificate->certificate_pdf)
            <div class="mb-3 text-muted small">
                Last uploaded by <strong>{{ $certificate->pdf_uploaded_by }}</strong>
                on {{ \Carbon\Carbon::parse($certificate->pdf_uploaded_at)->format('d M Y \a\t H:i') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <tbody>
                    <tr><th>Certificate Number</th><td>{{ $certificate->certificate_number }}</td></tr>
                    <tr>
                        <th>Certificate Validity</th>
                        <td>
                            @if ($certificate->status === 'Deleted')
                                <span class="text-danger">This certificate has been deleted</span>
                            @elseif ($certificate->status === 'Pending Review')
                                <span class="text-warning">Certificate Pending Review</span>
                            @elseif ($certificate->status === 'Pending Approval')
                                <span class="text-warning">Certificate Pending Approval</span>
                            @elseif (empty($certificate->validity_date) || ! \Carbon\Carbon::parse($certificate->validity_date)->isPast())
                                <span class="text-success">Certificate Valid</span>
                            @else
                                <span class="text-danger">Certificate Expired</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Approval Status</th>
                        <td><x-admin.status-badge :status="$certificate->status" /></td>
                    </tr>
                    <tr><th>Inspector / TUVAT Responsible</th><td>{{ $certificate->inspector }}</td></tr>
                    <tr><th>Client</th><td>{{ $certificate->client_name }}</td></tr>
                    <tr><th>Inspection Type</th><td>{{ $certificate->inspection_type }}</td></tr>
                    <tr><th>Inspection Location</th><td>{{ $certificate->inspection_location }}</td></tr>
                    <tr><th>Equipment/Item Name</th><td>{{ $certificate->equipment_name }}</td></tr>
                    <tr><th>Manufacturer / Brand</th><td>{{ $certificate->equipment_brand }}</td></tr>
                    <tr><th>Equipment Serial / Chassis Number</th><td>{{ $certificate->equipment_serial_chassis }}</td></tr>
                    <tr><th>Rated Capacity</th><td>{{ $certificate->equipment_rated_capacity }}</td></tr>
                    <tr><th>Equipment SWL</th><td>{{ $certificate->equipment_swl }}</td></tr>
                    <tr><th>Inspection Date</th><td>{{ \Carbon\Carbon::parse($certificate->inspection_date)->format('d M Y') }}</td></tr>
                    <tr>
                        <th>Valid Till</th>
                        <td>
                            @if (!empty($certificate->validity_date))
                                {{ \Carbon\Carbon::parse($certificate->validity_date)->format('d M Y') }}
                            @else
                                No Expiry Date
                            @endif
                        </td>
                    </tr>
                    <tr><th>Inspection Remarks</th><td>{{ $certificate->inspection_remarks }}</td></tr>
                    <tr><th>Internal Notes</th><td>{{ $certificate->inspection_internal_notes }}</td></tr>
                    <tr>
                        <th>Certificate PDF File</th>
                        <td>
                            @if($certificate->certificate_pdf)
                                <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">
                                    <strong>{{ $certificate->certificate_pdf }}</strong>
                                </a>
                            @else
                                <span class="text-danger">No certificate PDF uploaded yet</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Review By</th><td>{{ $certificate->review_by }}</td></tr>
                    <tr>
                        <th>Reviewed on</th>
                        <td>{{ $certificate->reviewed_at ? $certificate->reviewed_at->format('d M Y \a\t H:i:s') : 'Not yet reviewed' }}</td>
                    </tr>
                    <tr><th>Approval By</th><td>{{ $certificate->approval_by }}</td></tr>
                    <tr>
                        <th>Approved on</th>
                        <td>{{ $certificate->approved_at ? $certificate->approved_at->format('d M Y \a\t H:i:s') : 'Not yet approved' }}</td>
                    </tr>
                    <tr>
                        <th>QR Code</th>
                        <td>
                            @php
                                $verification_url = url('/') . '?search=' . $certificate->certificate_number;
                            @endphp
                            <img width="150" height="150" alt="QR code" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($verification_url) }}">
                        </td>
                    </tr>
                    <tr><th>Created By</th><td>{{ $certificate->created_by }}</td></tr>
                    <tr><th>Created On</th><td>{{ $certificate->created_at->format('d M Y \a\t H:i:s') }}</td></tr>
                    <tr><th>Last Updated By</th><td>{{ $certificate->updated_by }}</td></tr>
                    <tr><th>Updated On</th><td>{{ $certificate->updated_at ? $certificate->updated_at->format('d M Y \a\t H:i:s') : '' }}</td></tr>
                    <tr><th>Deleted by</th><td>{{ $certificate->status === 'Deleted' ? $certificate->deleted_by : 'N/A' }}</td></tr>
                    <tr>
                        <th>Deleted on</th>
                        <td>{{ $certificate->deleted_at ? $certificate->deleted_at->format('d M Y \a\t H:i:s') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($certificate->certificate_pdf)
            @php
                $viewerBase = asset('public/laraview/index.html');
                $pdfFolder = 'Certificate PDFs';
                $viewerSrc = $viewerBase
                    . '#../' . rawurlencode($pdfFolder)
                    . '/' . rawurlencode($certificate->certificate_pdf);
                $collapseId = 'pdfViewerCollapse-' . $certificate->id;
                $toggleId = 'togglePdfHeaderBtn-' . $certificate->id;
            @endphp

            <div class="admin-card mt-4">
                <div class="admin-card-header d-flex justify-content-between align-items-center">
                    <button
                        id="{{ $toggleId }}"
                        class="btn btn-link header-toggle d-flex align-items-center"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $collapseId }}"
                        aria-expanded="false"
                        aria-controls="{{ $collapseId }}">
                        <i class="fa-solid fa-chevron-right me-2 chev"></i>
                        <span>Certificate PDF Preview</span>
                    </button>
                    <small class="text-muted">
                        If it doesn't load, <a href="{{ route('certificate.downloadPdf', $certificate->id) }}" target="_blank">download</a>.
                    </small>
                </div>
                <div class="collapse" id="{{ $collapseId }}">
                    <div class="admin-card-body p-0" style="height: 75vh;">
                        <iframe
                            data-viewer-src="{{ $viewerSrc }}"
                            title="Certificate PDF"
                            style="width:100%; height:100%; border:0;"
                            allow="fullscreen"
                            loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning mt-4">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                No certificate PDF uploaded yet.
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
@if($certificate->certificate_pdf)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapseEl = document.getElementById(@json($collapseId));
    const btn = document.getElementById(@json($toggleId));
    if (!collapseEl || !btn) return;

    const iframe = collapseEl.querySelector('iframe');

    collapseEl.addEventListener('show.bs.collapse', function () {
        if (!iframe.getAttribute('src')) {
            iframe.setAttribute('src', iframe.dataset.viewerSrc);
        }
        btn.setAttribute('aria-expanded', 'true');
    });

    collapseEl.addEventListener('hide.bs.collapse', function () {
        btn.setAttribute('aria-expanded', 'false');
    });
});
</script>
@endif
@endpush
