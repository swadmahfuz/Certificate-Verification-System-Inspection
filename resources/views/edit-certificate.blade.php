@extends('layouts.admin')

@section('title', 'Edit Certificate')

@section('content')
<div class="page-heading">
    <div>
        <h1>Edit Certificate</h1>
        <p>{{ $certificate->certificate_number }}</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-outline-primary btn-sm" href="{{ route('certificate.view', $certificate->id) }}">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to details
        </a>
        <form action="{{ route('certificate.delete', $certificate->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" data-confirm="Delete this certificate?">
                <i class="fa-solid fa-trash me-1"></i> Delete
            </button>
        </form>
    </div>
</div>

<section class="admin-card">
    <div class="admin-card-header">
        <h2>Certificate Details</h2>
        <span class="small text-muted">* Required fields</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('certificate.update') }}">
            @csrf
            <input type="hidden" name="id" value="{{ $certificate->id }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="certificate_number">Certificate Number *</label>
                    @error('certificate_number') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="certificate_number" name="certificate_number" class="form-control" value="{{ $certificate->certificate_number }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspector">Inspector Name *</label>
                    @error('inspector') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="inspector" name="inspector" class="form-control" value="{{ $certificate->inspector }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="client_name">Client Name *</label>
                    @error('client_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="client_name" name="client_name" class="form-control" value="{{ $certificate->client_name }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_type">Inspection Type *</label>
                    @error('inspection_type') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="inspection_type" name="inspection_type" class="form-control" value="{{ $certificate->inspection_type }}">
                </div>

                <div class="col-12">
                    <label class="form-label" for="inspection_location">Inspection Location *</label>
                    @error('inspection_location') <div class="text-danger small">{{ $message }}</div> @enderror
                    <textarea id="inspection_location" name="inspection_location" class="form-control">{{ $certificate->inspection_location }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="equipment_name">Equipment Name *</label>
                    @error('equipment_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="equipment_name" name="equipment_name" class="form-control" value="{{ $certificate->equipment_name }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="equipment_brand">Equipment Brand</label>
                    <input type="text" id="equipment_brand" name="equipment_brand" class="form-control" value="{{ $certificate->equipment_brand }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_serial_chassis">Serial/Chassis No</label>
                    <input type="text" id="equipment_serial_chassis" name="equipment_serial_chassis" class="form-control" value="{{ $certificate->equipment_serial_chassis }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_rated_capacity">Rated Capacity</label>
                    <input type="text" id="equipment_rated_capacity" name="equipment_rated_capacity" class="form-control" value="{{ $certificate->equipment_rated_capacity }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_swl">SWL</label>
                    <input type="text" id="equipment_swl" name="equipment_swl" class="form-control" value="{{ $certificate->equipment_swl }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_date">Inspection Date *</label>
                    @error('inspection_date') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="date" id="inspection_date" name="inspection_date" class="form-control" value="{{ $certificate->inspection_date }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="validity_date">Validity Date</label>
                    <input type="date" id="validity_date" name="validity_date" class="form-control" value="{{ $certificate->validity_date }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_remarks">Remarks</label>
                    <textarea id="inspection_remarks" name="inspection_remarks" class="form-control">{{ $certificate->inspection_remarks }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_internal_notes">Internal Notes</label>
                    <textarea id="inspection_internal_notes" name="inspection_internal_notes" class="form-control">{{ $certificate->inspection_internal_notes }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="review_by">Review by *</label>
                    @error('review_by') <div class="text-danger small">{{ $message }}</div> @enderror
                    <select id="review_by" name="review_by" class="form-select">
                        <option value="">Select Reviewer</option>
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" {{ $certificate->review_by == $user->name ? 'selected' : '' }}>{{ $user->name }} | {{ $user->designation }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="approval_by">Approval by *</label>
                    @error('approval_by') <div class="text-danger small">{{ $message }}</div> @enderror
                    <select id="approval_by" name="approval_by" class="form-select">
                        <option value="">Select Approver</option>
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" {{ $certificate->approval_by == $user->name ? 'selected' : '' }}>{{ $user->name }} | {{ $user->designation }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check me-1"></i> Update Certificate
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
