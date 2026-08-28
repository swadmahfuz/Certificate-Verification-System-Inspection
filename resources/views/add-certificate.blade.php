@extends('layouts.admin')

@section('title', 'Add Certificate')

@section('content')
<div class="page-heading">
    <div>
        <h1>Add Inspection Certificate</h1>
        <p>Create a new inspection certificate record.</p>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('certificates.index') }}">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to certificates
    </a>
</div>

<section class="admin-card">
    <div class="admin-card-header">
        <h2>Certificate Details</h2>
        <span class="small text-muted">* Required fields</span>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('certificate.create') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="certificate_number">Certificate Number *</label>
                    @error('certificate_number') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="certificate_number" name="certificate_number" class="form-control" value="INSP-TUVAT-{{ $currentYear }}-{{ $currentMonthDay }}-">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspector">Inspector *</label>
                    @error('inspector') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="inspector" name="inspector" class="form-control" value="{{ old('inspector') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="client_name">Client Name *</label>
                    @error('client_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="client_name" name="client_name" class="form-control" value="{{ old('client_name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_type">Inspection Type *</label>
                    @error('inspection_type') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="inspection_type" name="inspection_type" class="form-control" value="{{ old('inspection_type') }}">
                </div>

                <div class="col-12">
                    <label class="form-label" for="inspection_location">Inspection Location *</label>
                    @error('inspection_location') <div class="text-danger small">{{ $message }}</div> @enderror
                    <textarea id="inspection_location" name="inspection_location" class="form-control">{{ old('inspection_location') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="equipment_name">Equipment Name *</label>
                    @error('equipment_name') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="text" id="equipment_name" name="equipment_name" class="form-control" value="{{ old('equipment_name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="equipment_brand">Equipment Brand</label>
                    <input type="text" id="equipment_brand" name="equipment_brand" class="form-control" value="{{ old('equipment_brand') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_serial_chassis">Serial/Chassis No.</label>
                    <input type="text" id="equipment_serial_chassis" name="equipment_serial_chassis" class="form-control" value="{{ old('equipment_serial_chassis') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_rated_capacity">Rated Capacity</label>
                    <input type="text" id="equipment_rated_capacity" name="equipment_rated_capacity" class="form-control" value="{{ old('equipment_rated_capacity') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="equipment_swl">SWL</label>
                    <input type="text" id="equipment_swl" name="equipment_swl" class="form-control" value="{{ old('equipment_swl') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_date">Inspection Date *</label>
                    @error('inspection_date') <div class="text-danger small">{{ $message }}</div> @enderror
                    <input type="date" id="inspection_date" name="inspection_date" class="form-control" value="{{ old('inspection_date') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="validity_date">Validity Date</label>
                    <input type="date" id="validity_date" name="validity_date" class="form-control" value="{{ old('validity_date') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_remarks">Inspection Remarks</label>
                    <textarea id="inspection_remarks" name="inspection_remarks" class="form-control">{{ old('inspection_remarks') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="inspection_internal_notes">Internal Notes</label>
                    <textarea id="inspection_internal_notes" name="inspection_internal_notes" class="form-control">{{ old('inspection_internal_notes') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="review_by">Review by *</label>
                    @error('review_by') <div class="text-danger small">{{ $message }}</div> @enderror
                    <select id="review_by" name="review_by" class="form-select">
                        <option value="">Select Reviewer</option>
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" {{ old('review_by') === $user->name ? 'selected' : '' }}>{{ $user->name }} | {{ $user->designation }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="approval_by">Approval by *</label>
                    @error('approval_by') <div class="text-danger small">{{ $message }}</div> @enderror
                    <select id="approval_by" name="approval_by" class="form-select">
                        <option value="">Select Approver</option>
                        @foreach($users as $user)
                            <option value="{{ $user->name }}" {{ old('approval_by') === $user->name ? 'selected' : '' }}>{{ $user->name }} | {{ $user->designation }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check me-1"></i> Add Certificate
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
