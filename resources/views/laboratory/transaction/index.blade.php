@extends('layouts.labadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-success">Transactions</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a class="btn btn-primary float-right btn-sm" href="{{ route('transaction.print-data') }}">
                                <i class="fas fa-print"></i>
                                Print
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Quantity</th>
                                            <th>Purpose</th>
                                            <th>Datetime Borrowed</th>
                                            <th>Status</th>
                                            <th>Days Not Returned</th>
                                            <th>Datetime Returned</th>
                                            @hasrole('laboratory')
                                            <th>Actions</th>
                                            @endhasrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $requisition)
                                        <tr>
                                            <td>{{ $requisition->id }}</td>
                                            <td>{{ $requisition->instructor->name }}</td>
                                            <td>
                                                {{ $requisition->items[0]->quantity ?? '' }}
                                            </td>
                                            <td>
                                                {{ $requisition->activity }}
                                            </td>
                                            <td>
                                                {{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                                            </td>
                                            <td>{{ $requisition->status }}
                                            </td>
                                            <td>
                                                {{ $requisition->status === 'Returned' ? '' :
                                                \Carbon\Carbon::parse($requisition->date_time_filed)->diffInDays(now())
                                                . ' days' }}

                                            </td>

                                            <td>
                                                {{ $requisition->status === 'Returned' ? date('F d, Y h:i A',
                                                strtotime($requisition->returned_date)) : '' }}
                                            </td>
                                            @hasrole('laboratory')
                                            <td class="d-flex flex-auto">
                                                <a class="btn btn-primary btn-sm mr-3"
                                                    href="{{ route('borrows.show', ['id' => $requisition->id]) }}">
                                                    <i class="fas fa-eye"></i>
                                                    View Requisition Details
                                                </a>
                                            </td>
                                            @endhasrole
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to set the status to <strong id="statusText"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger bg-red-500 text-white" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success bg-green-600 text-white"
                    id="confirmStatusBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
            var selectedStatus = '';
            var requestId = '';
            $('.status-option').on('click', function(event) {
                event.preventDefault();
                selectedStatus = $(this).data('status');
                requestId = $(this).data('id');
                $('#statusText').text(selectedStatus);
                $('#statusModal').modal('show');
            });

            $('#confirmStatusBtn').on('click', function() {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route('lab.transactions.update') }}',
                    method: 'POST',
                    data: {
                        id: requestId,
                        status: selectedStatus,
                        _token: csrfToken
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        setTimeout(() => {
                            location.reload()
                        }, 1500);
                        console.log(response)
                    },
                });
            });
        });

        $(document).ready(function() {

            let requestIds = [];
            $('#example1 tbody tr').each(function() {
                const row = $(this);
                const requestId = row.find('td:nth-child(1)').text().trim();
                const dateText = row.find('td:nth-child(5)').text().trim(); // Parse the date
                const dateValue = new Date(dateText); // Get the current date
                const currentDate = new Date(); // Calculate the difference in time
                const diffTime = Math.abs(currentDate - dateValue); // Convert time difference to days
                const daysNotReturned = Math.ceil(diffTime / (1000 * 60 * 60 *
                    24)); // Use the daysNotReturned value as needed
                console.log(daysNotReturned);
                const status = row.find('td:nth-child(6)').text().trim();

                if (daysNotReturned >= 3 && status === 'Received') {
                    requestIds.push(requestId);
                    $.ajax({
                        url: '{{ route('notify.user') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            request_ids: requestIds,
                        },
                        success: function(response) {
                            if (status !== 'Returned') {
                                row.addClass('table-danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(
                                `Failed to notify borrower for request ID ${requestId}: ${error}`
                            );
                        }
                    });
                }
            });
        });

        // document.getElementById('print-btn').addEventListener('click', function() {
        //     let dataTable = document.querySelector('#example1').cloneNode(true);

        //     let theadRow = dataTable.querySelector('thead tr');
        //     theadRow.removeChild(theadRow.lastElementChild);

        //     let tbodyRows = dataTable.querySelectorAll('tbody tr');
        //     tbodyRows.forEach(function(row) {
        //         row.removeChild(row.lastElementChild);
        //     });

        //     let printWindow = window.open('', '', 'width=1200,height=1200');

        //     printWindow.document.write(`
        //         <html>
        //         <head>
        //             <title>Print Data</title>
        //             <style>
        //                 @media print {
        //             @page {
        //                 size: landscape;
        //                 margin: 1cm;
        //             }

        //             body {
        //                 font-family: "Times New Roman", serif;
        //             }

        //             h1,
        //             h2,
        //             h3 {
        //                 text-align: center;
        //             }

        //             h1 {
        //                 font-family: "Old English Text MT", serif;
        //             }

        //             h2 {
        //                 font-weight: bold;
        //                 text-transform: uppercase;
        //             }

        //             table {
        //                 width: 100%;
        //                 border-collapse: collapse;
        //                 margin-top: 20px;
        //             }

        //             table th,
        //             table td {
        //                 border: 1px solid black;
        //                 padding: 5px;
        //                 text-align: left;
        //                 font-size: 12px;
        //             }

        //             table th {
        //                 background-color: #f2f2f2;
        //             }
        //         }

        //         .header {
        //             display: flex;
        //             align-items: center;
        //             justify-content: center;
        //             margin-bottom: 20px;
        //         }

        //         .header img {
        //             width: 80px;
        //             height: 80px;
        //             margin-right: 10px;
        //         }

        //         .header-content {
        //             text-align: center;
        //         }

        //         .header-content h1 {
        //             font-weight: normal;
        //             font-family: "Old English Text MT", serif;
        //             margin: 0;
        //         }

        //         .header-content h3 {
        //             font-family: "Times New Roman", serif;
        //             margin: 0;
        //         }

        //         .school-title {
        //             text-align: center;
        //             margin-bottom: 20px;
        //         }
        //             </style>
        //         </head>
        //         <body>
        //             <div class="header">
        //                 <img src="{{ asset('dist/img/spup1.png') }}" alt="Logo">
        //                 <div class="header-content">
        //                     <h1>Saint Paul University Philippines</h1>
        //                     <h3>Tuguegarao City, Cagayan 3500</h3>
        //                 </div>
        //             </div>

        //             <div class="school-title">
        //                 <h4>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</h4>
        //                 <h5><strong>LABORATORY TRANSACTION</strong></h5>
        //             </div>
        //             ${dataTable.outerHTML}
        //         </body>
        //         </html>
        //     `);

        //     printWindow.document.close();

        //     printWindow.onload = function() {
        //         printWindow.print();
        //         printWindow.close();
        //     };
        // });
</script>
@endsection