<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="noindex">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>View Certificate Information</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />     
    </head>
    <body background="../images/tuv-login-background1.jpg">
        <section style="padding-top: 60px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card">
                            <div class="card-header" ><center><h3>Detailed Certificate Information</h3></center>
                                <center>
                                    <a href="../dashboard" class="btn btn-primary">Go back to Dashboard</a> 
                                    <a href="../delete-certificate/{{ $certificate->id }}" class="btn btn-danger">Delete Certificate</a>
                                </center> 
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" style="width:60%; margin-left:20%; margin-right:20%; border: 1px solid black;">

                                    <tr>
                                        <th>Certificate Number</th>
                                        <td>{{ $certificate->certificate_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Client</th>
                                        <td>{{ $certificate->client_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Type</th>
                                        <td>{{ $certificate->inspection_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Location</th>
                                        <td>{{ $certificate->inspection_location }}</td>
                                    </tr>
                                    <tr>
                                        <th>Equipment/Item</th>
                                        <td>{{ $certificate->equipment_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Manufacturer/Brand</th>
                                        <td>{{ $certificate->equipment_brand }}</td>
                                    </tr>
                                    <tr>
                                        <th>Serial/Chassis Number</th>
                                        <td>{{ $certificate->equipment_serial_chassis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Rated Capacity</th>
                                        <td>{{ $certificate->equipment_rated_capacity }}</td>
                                    </tr>
                                    <tr>
                                        <th>SWL</th>
                                        <td>{{ $certificate->equipment_swl }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Date</th>
                                        <td>{{ $certificate->inspection_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Valid Till</th>
                                        <td>{{ $certificate->validity_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Inspection Remarks</th>
                                        <td>{{ $certificate->inspection_remarks }}</td>
                                    </tr>
                                    <tr>
                                        <th>Internal Notes</th>
                                        <td>{{ $certificate->inspection_internal_notes }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created by</th>
                                        <td>{{ $certificate->created_by }}</td>
                                    </tr>
                                    <tr>
                                        <th>QR Code</th>
                                        @php
                                            $url = url('');  ///capture server url
                                            $verification_url = $url.'?search='.$certificate->certificate_number;   ///concat server url with verification link and certificate number
                                        @endphp
                                        {{-- The line below uses generate qr code image --}}
                                        <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $verification_url }}"/></td> 
                                    </tr>
                                    <tr>
                                        <th>Last Updated by</th>
                                        <td>{{ $certificate->updated_by }}</td>
                                    </tr>
                                    <tr>
                                        <th>Deleted by</th>
                                        <td>{{ $certificate->deleted_by }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
    @include('layouts.footer')  <!-- Including the footer Blade file -->
</html>
