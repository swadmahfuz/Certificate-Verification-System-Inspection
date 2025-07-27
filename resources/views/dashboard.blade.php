<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TÜV Austria BIC CVS | Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <style>
        .container { max-width: 99%; }
        .table-container { overflow-x: auto; }
        .table-striped thead th {
            text-align: left;
            position: sticky;
            top: 0;
            background-color: rgb(243, 243, 243);
            border-right: 1px solid #dee2e6;
        }
        .table-striped thead th:last-child { border-right: none; }
        .table-striped tbody td, .table-striped thead th { vertical-align: middle; }
        .table-striped { font-size: 11px; }
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn i { font-size: 16px; }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body background="images/tuv-login-background1.jpg">
<section style="padding-top: 60px;">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h6 class="text-end">Logged in User: <b>{{ auth()->user()->name }} ({{ auth()->user()->designation }})</b></h6>
                <center><h3 class="mb-3">TÜV Austria BIC - Inspection Certificate Verification System (CVS)</h3></center>
                <table class="mb-2" style="width: 90%; margin: auto;">
                    <tr>
                        <td><a href="add-certificate" class="btn btn-success"><i class="fa-solid fa-plus me-1"></i> Add New Certificate</a></td>
                        <td><a href="dashboard" class="btn btn-primary"><i class="fa-solid fa-arrows-rotate me-1"></i> Refresh</a></td>
                        <td><a href="pending-certificates" class="btn btn-info"><i class="fa-solid fa-clock me-1"></i> Pending Certificates</a></td>
                        <td><a href="imports-exports" class="btn btn-warning"><i class="fa-solid fa-file-import me-1"></i> Import/Export Data</a></td>
                        <td><a href="all-users" class="btn btn-secondary"><i class="fa-solid fa-users me-1"></i> View All Users</a></td>
                        <td><a href="logout" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Log Out</a></td>
                    </tr>
                </table>

                <div class="text-center my-2" style="width: 35%; margin: auto;">
                    <input type="text" class="form-control search-input" placeholder="Search Certificates">
                </div>
            </div>

            <div class="card-body">
                @if (Session::has('post-deleted'))
                    <div class="alert-success" role="alert">{{ Session::get('post_deleted') }}</div>
                @endif
                <table class="table table-striped search-result">
                    <thead>
                        <tr><th colspan="12" class="text-center fs-5">All Certificates</th></tr>
                        <tr>
                            <th>Sl.</th>
                            <th>Certificate No</th>
                            <th>Inspector</th>
                            <th>Client</th>
                            <th>Inspection Type</th>
                            <th>Equipment</th>
                            <th>Inspection Date</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th>QR</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentPage = $certificates->currentPage();
                            $perPage = $certificates->perPage();
                            $offset = ($currentPage - 1) * $perPage;
                        @endphp
                        @foreach ($certificates as $certificate)
                            @php
                                $url = url('');
                                $verification_url = $url . '?search=' . $certificate->certificate_number;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + $offset }}.</td>
                                <td>{{ $certificate->certificate_number }}</td>
                                <td>{{ $certificate->inspector }}</td>
                                <td>{{ $certificate->client_name }}</td>
                                <td>{{ $certificate->inspection_type }}</td>
                                <td>{{ $certificate->equipment_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($certificate->inspection_date)->format('d-m-Y') }}</td>
                                <td>
                                    @if ($certificate->validity_date)
                                        {{ \Carbon\Carbon::parse($certificate->validity_date)->format('d-m-Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $certificate->status }}</td>
                                <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $verification_url }}"/></td>
                                <td>
                                    <a href="view-certificate/{{ $certificate->id }}" class="me-1" target="_blank"><i class="fa-solid fa-circle-info" title="View Certificate"></i></a>
                                    <a href="edit-certificate/{{ $certificate->id }}" class="me-1" target="_blank"><i class="fa-solid fa-pen-to-square" title="Edit Certificate"></i></a>
                                    <a href="delete-certificate/{{ $certificate->id }}" class="me-1"><i class="fa-solid fa-trash" title="Delete Certificate"></i></a>
                                    @if(Auth::check() && (Auth::user()->id == $certificate->review_by_id || Auth::user()->name == $certificate->review_by) && $certificate->status == 'Pending Review')
                                        <a href="{{ route('certificate.review', $certificate->id) }}" class="me-1"><i class="fa-solid fa-thumbs-up" title="Mark as Reviewed"></i></a>
                                    @endif
                                    @if(Auth::check() && (Auth::user()->id == $certificate->approval_by_id || Auth::user()->name == $certificate->approval_by) && $certificate->status == 'Pending Approval')
                                        <a href="{{ route('certificate.approve', $certificate->id) }}"><i class="fa-solid fa-check" title="Mark as Approved"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $certificates->links() }}
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        function fetchCertificates(page = 1, userInput = '') {
            $.ajax({
                url: "{{ url('live-search') }}",
                data: { userInput, page },
                dataType: 'json',
                beforeSend: function () {
                    $(".search-result tbody").html('<tr><td colspan="11">Searching...</td></tr>');
                },
                success: function (res) {
                    var _html = '';
                    $.each(res.data.data, function (index, cert) {
                        let verifyUrl = "{{ url('') }}" + "?search=" + cert.certificate_number;
                        _html += `<tr>
                            <td>${index + 1 + (res.data.current_page - 1) * res.data.per_page}.</td>
                            <td>${cert.certificate_number}</td>
                            <td>${cert.inspector}</td>
                            <td>${cert.client_name}</td>
                            <td>${cert.inspection_type}</td>
                            <td>${cert.equipment_name}</td>
                            <td>${formatDate(cert.inspection_date)}</td>
                            <td>${formatDate(cert.validity_date)}</td>
                            <td>${cert.status}</td>
                            <td><img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=${verifyUrl}"/></td>
                            <td>
                                <a href="view-certificate/${cert.id}" target="_blank"><i class="fa-solid fa-circle-info"></i></a>
                                <a href="edit-certificate/${cert.id}" target="_blank"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="delete-certificate/${cert.id}"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>`;
                    });

                    if (_html === '') {
                        _html = '<tr><td colspan="11" class="text-center">No matching certificates found.</td></tr>';
                    }

                    $(".search-result tbody").html(_html);
                    $('.pagination').remove();
                    $('.card-body').append(generatePaginationLinks(res.data));
                }
            });
        }

        function formatDate(date) {
            if (!date) return 'N/A';
            let d = new Date(date);
            return ('0' + d.getDate()).slice(-2) + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
        }

        function generatePaginationLinks(data) {
            let links = '<nav class="pagination-container"><ul class="pagination">';
            if (data.current_page > 1) {
                links += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}">&laquo;</a></li>`;
            }
            for (let i = 1; i <= data.last_page; i++) {
                links += `<li class="page-item${i === data.current_page ? ' active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            if (data.current_page < data.last_page) {
                links += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}">&raquo;</a></li>`;
            }
            links += '</ul></nav>';
            return links;
        }

        $(".search-input").on('keyup', function () {
            fetchCertificates(1, $(this).val());
        });

        $(document).on('click', '.pagination a', function (e) {
            e.preventDefault();
            fetchCertificates($(this).data('page'), $('.search-input').val());
        });

        fetchCertificates();
    });
</script>
</body>
<footer>@include('layouts.footer')</footer>
</html>
