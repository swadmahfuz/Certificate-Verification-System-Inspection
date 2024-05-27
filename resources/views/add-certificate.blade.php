<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Add Certificate</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
</head>
    <body background="images/tuv-login-background1.jpg">
        <section style="padding-top: 60px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 offset-md-3">
                        <div class="card">
                            <div class="card-header" >
                                <center><h3>Add Certificate Information</h3></center>
                                <center>
                                    <a href="./dashboard" class="btn btn-primary">Go back to Dashboard</a> 
                                    <h6 style="text-align: right">* Required fields</h6>
                                </center> 
                            </div>
                            <div class="card-body">
                                
                                <form class="col s12" method="POST" action="{{ route('certificate.create') }}">
                                    @csrf
                                    <div class="form-group">
                                        
                                        <label for="certificate_number">Certificate Number*</label>
                                        @error('certificate_number')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="certificate_number" class="form-control" placeholder="Enter Certificate Number" value="">
                                        <br>

                                        <label for="inspector">Inspector/Responsible Person*</label>
                                        @error('client_name')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="inspector" class="form-control" placeholder="Enter Inspector/Responsible Person" value="{{ old('client_name') }}">
                                        <br>
                                        
                                        <label for="client_name">Client*</label>
                                        @error('client_name')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="client_name" class="form-control" placeholder="Enter Client Name" value="{{ old('client_name') }}">
                                        <br>

                                        <label for="inspection_type">Inspection Type*</label>
                                        @error('inspection_type')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="inspection_type" class="form-control" placeholder="Enter Inspection Type" value="{{ old('inspection_type') }}">
                                        <br>

                                        <label for="inspection_location">Inspection Location*</label>
                                        @error('inspection_location')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="inspection_location" class="form-control" placeholder="Enter Inspection Location" value="{{ old('inspection_location') }}">
                                        <br>

                                        <label for="equipment_name">Equipment/Item*</label>
                                        @error('equipment_name')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="equipment_name" class="form-control" placeholder="Enter Equipment/Item Name" value="{{ old('equipment_name') }}">
                                        <br>

                                        <label for="equipment_brand">Equipment Brand/Manufacturer*</label>
                                        @error('equipment_brand')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="equipment_brand" class="form-control" placeholder="Enter Item Manufacturer/Brand" value="{{ old('equipment_brand') }}">
                                        <br>

                                        <label for="equipment_serial_chassis">Serial/Chassis No.*</label>
                                        @error('equipment_serial_chassis')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="text" name="equipment_serial_chassis" class="form-control" placeholder="Enter Serial/Chassis No." value="{{ old('equipment_serial_chassis') }}">
                                        <br> 

                                        <label for="equipment_rated_capacity">Rated Capacity</label>
                                        <input type="text" name="equipment_rated_capacity" class="form-control" placeholder="Enter Rated Capacity" value="{{ old('equipment_rated_capacity') }}">
                                        <br>
                                         

                                        <label for="equipment_swl">SWL</label>
                                        <input type="text" name="equipment_swl" class="form-control" placeholder="Enter Safe Working Load" value="{{ old('equipment_swl') }}">
                                        <br>

                                        <label for="inspection_date">Inspection Date*</label>
                                        @error('inspection_date')
                                            <span class="text-danger">{{$message}}</span> <br> 
                                        @enderror
                                        <input type="date" name="inspection_date" class="form-control" placeholder="Enter Inspection Date" value="{{ old('inspection_date') }}">
                                        <br> 

                                        <label for="validity_date">Validity Expiration Date</label>
                                        <input type="date" name="validity_date" class="form-control" placeholder="Enter Certificate Expiration Date" value="{{ old('validity_date') }}">
                                        <br>

                                        <label for="inspection_remarks">Inspection Remarks</label>
                                        <textarea name="inspection_remarks" class="form-control" placeholder="Enter any inspection remarks." rows="4">{{ old('inspection_remarks') }}</textarea>
                                        <br>

                                        <label for="inspection_internal_notes">Inspection Notes (for internal use only)</label>
                                        <textarea name="inspection_internal_notes" class="form-control" placeholder="Enter any inspection notes for internal use only. Not visible to public." rows="4">{{ old('inspection_internal_notes') }}</textarea>
                                        <br>

                                        
                                        <center><button type="submit" class="btn btn-success">Add Details</button></center>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
    <footer> @include('layouts.footer')  <!-- Including the footer Blade file --> </footer>
</html>
