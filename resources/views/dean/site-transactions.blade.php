@extends('layouts.dean')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Transactions</h1>
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
                                <div class="flex float-right">
                                    <button class="btn btn-primary btn-sm ml-2" id="print-btn">
                                        <i class="fas fa-print"></i> Print
                                    </button>

                                    <button class="btn btn-primary btn-sm ml-2" id="print-all-btn">
                                        <i class="fas fa-print"></i> Print All
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Purpose</th>
                                            <th>Datetime Borrowed</th>
                                            <th>Status</th>
                                            <th>Days Not Returned</th>
                                            <th>Datetime Returned</th>
                                            @hasrole('site scretary')
                                                <th>Actions</th>
                                            @endhasrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requests as $request)
                                            <tr>
                                                <td>{{ $request->id }}</td>
                                                <td>{{ $request->requested_by_name }}</td>
                                                <td>
                                                    @if ($request->item_type === 'Supplies')
                                                        {{ $request->item_name }}
                                                    @elseif ($request->item_type === 'Equipments')
                                                        <ul>
                                                            <li>{{ $request->item_name }} - {{ $request->serial_no }}</li>
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ $request->quantity_requested }}</td>
                                                <td>{{ $request->purpose }}</td>
                                                <td>{{ $request->created_at }}</td>
                                                <td>{{ $request->status }}</td>
                                                <td>
                                                    {{ now()->diffInDays($request->created_at) }}
                                                </td>
                                                <td>
                                                    @if ($request->status === 'Returned')
                                                        {{ date('Fd y h:i A', strtotime($request->updated_at)) }}
                                                    @endif
                                                </td>
                                                @hasrole('site scretary')
                                                    <td>
                                                        @if ($request->status === 'Pending' && $request->item_type === 'Equipments')
                                                            <a class="btn btn-success status-option" data-status="Approved"
                                                                data-id="{{ $request->id }}" href="#">Approve</a>
                                                            <a class="btn btn-danger status-option" data-status="Declined"
                                                                data-id="{{ $request->id }}" href="#">Decline</a>
                                                        @elseif ($request->status === 'Approved' && $request->item_type === 'Equipments')
                                                            <a class="btn btn-success status-option" data-status="Received"
                                                                data-id="{{ $request->id }}" href="#">Received</a>
                                                        @elseif ($request->status === 'Received' && $request->item_type === 'Equipments')
                                                            <div class="dropdown">
                                                                <a class="btn btn-secondary dropdown-toggle" href="#"
                                                                    role="button" data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-cog"></i> Configure
                                                                </a>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item status-option"
                                                                        data-status="Returned" data-id="{{ $request->id }}"
                                                                        href="#">Returned</a>
                                                                    <a class="dropdown-item status-option" data-status="Damaged"
                                                                        data-id="{{ $request->id }}"
                                                                        href="#">Damaged</a>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($request->status === 'Pending' && $request->item_type === 'Supplies')
                                                            <div class="dropdown">
                                                                <a class="btn btn-secondary dropdown-toggle" href="#"
                                                                    role="button" data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-cog"></i> Configure
                                                                </a>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item status-option"
                                                                        data-status="Approved" data-id="{{ $request->id }}"
                                                                        href="#">Approved</a>
                                                                    <a class="dropdown-item status-option"
                                                                        data-status="Disapproved" data-id="{{ $request->id }}"
                                                                        href="#">Disapproved</a>
                                                                </div>
                                                            </div>
                                                        @elseif($request->status === 'Approved' && $request->item_type === 'Supplies')
                                                            <a class="btn btn-success status-option" data-status="Received"
                                                                data-id="{{ $request->id }}" href="#">Received</a>
                                                        @endif
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
                    <button type="button" class="btn bg-red-500 text-white" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-green-600 text-white" id="confirmStatusBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.getElementById('print-btn').addEventListener('click', function() {
            let dataTable = document.querySelector('#example1').cloneNode(true);

            let theadRow = dataTable.querySelector('thead tr');
            theadRow.removeChild(theadRow.lastElementChild);

            let tbodyRows = dataTable.querySelectorAll('tbody tr');
            tbodyRows.forEach(function(row) {
                row.removeChild(row.lastElementChild);
            });

            let printWindow = window.open('', '', 'width=1200,height=1200');

            printWindow.document.write(`
<html>
<head>
    <title>Print Computer Engineering Data</title>
<style>
    @media print {
        @page {
            size: landscape;
            margin: 1cm;
        }

        body {
            font-family: "Times New Roman", serif;
        }

        h1,
        h2,
        h3 {
            text-align: center;
        }

        h1 {
            font-family: "Old English Text MT", serif;
        }

        h2 {
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        table th {
            background-color: #f2f2f2;
        }
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .header img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }

    .header-content {
        text-align: center;
    }

    .header-content h1 {
        font-weight: normal;
        font-family: "Old English Text MT", serif;
        margin: 0;
    }

    .header-content h3 {
        font-family: "Times New Roman", serif;
        margin: 0;
    }

    .school-title {
        text-align: center;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('dist/img/spup1.png') }}" alt="Logo">
        <div class="header-content">
            <h1>Saint Paul University Philippines</h1>
            <h3>Tuguegarao City, Cagayan 3500</h3>
        </div>
    </div>

    <div class="school-title">
        <h4>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</h4>
        <h5><strong>SITE OFFICE TRANSACTIONS</strong></h5>
    </div>
    ${dataTable.outerHTML}
</body>
</html>
`);

            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        });

        $(document).ready(function() {
            $('#print-all-btn').on('click', function() {
                // Check if the DataTable is initialized
                var table = $('#example1').DataTable();

                if (table) {
                    // Destroy the DataTable instance before printing
                    table.destroy();
                }

                // Now remove the 'id' and any other DataTable related properties
                var tableElement = $('#example1'); // Get the table element itself
                tableElement.removeAttr('id').removeClass('dataTable');

                // Clone the table (without DataTable functionalities)
                var dataTableClone = tableElement.clone(true,
                    true); // Clone with all child elements and events

                // Remove the last child of the header row
                let theadRow = dataTableClone.find('thead tr'); // Get the header row from the cloned table
                theadRow.each(function() {
                    $(this).children('th:last-child')
                        .remove(); // Remove the last <th> in each <thead> row
                });

                // Remove the last child from each row in the tbody
                let tbodyRows = dataTableClone.find(
                    'tbody tr'); // Get all rows from the tbody of the cloned table
                tbodyRows.each(function() {
                    $(this).children('td:last-child')
                        .remove(); // Remove the last <td> in each <tbody> row
                });

                // Open a new window for printing
                var printWindow = window.open('', '', 'width=1200,height=1200');

                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print Data</title>
                            <style>
                                @media print {
                                    @page {
                                        size: landscape;
                                        margin: 1cm;
                                    }
                                    body {
                                        font-family: "Times New Roman", serif;
                                    }
                                    h1, h2, h3 {
                                        text-align: center;
                                    }
                                    h1 {
                                        font-family: "Old English Text MT", serif;
                                    }
                                    h2 {
                                        font-weight: bold;
                                        text-transform: uppercase;
                                    }
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                        margin-top: 20px;
                                    }
                                    table th, table td {
                                        border: 1px solid black;
                                        padding: 5px;
                                        text-align: left;
                                        font-size: 12px;
                                    }
                                    table th {
                                        background-color: #f2f2f2;
                                    }
                                }
                                .header {
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-bottom: 20px;
                                }
                                .header img {
                                    width: 80px;
                                    height: 80px;
                                    margin-right: 10px;
                                }
                                .header-content {
                                    text-align: center;
                                }
                                .header-content h1 {
                                    font-weight: normal;
                                    font-family: "Old English Text MT", serif;
                                    margin: 0;
                                }
                                .header-content h3 {
                                    font-family: "Times New Roman", serif;
                                    margin: 0;
                                }
                                .school-title {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <img src="{{ asset('dist/img/spup1.png') }}" alt="Logo">
                                <div class="header-content">
                                    <h1>Saint Paul University Philippines</h1>
                                    <h3>Tuguegarao City, Cagayan 3500</h3>
                                </div>
                            </div>

                            <div class="school-title">
                                <h4>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</h4>
                                <h5><strong>SITE OFFICE TRANSACTIONS</strong></h5>
                            </div>
                            ${dataTableClone[0].outerHTML} <!-- Add cloned table -->
                        </body>
                    </html>
                `);

                printWindow.document.close();

                // Delay print to ensure content is fully rendered
                printWindow.onload = function() {
                    setTimeout(function() {
                        printWindow.print();
                        printWindow.close();
                    }, 500);
                };
            });
        });
    </script>
    @hasrole('laboratory')
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
                        url: '{{ route('office.transactions.update') }}',
                        method: 'POST',
                        data: {
                            id: requestId,
                            status: selectedStatus,
                            _token: csrfToken
                        },
                        dataType: 'json',
                        success: function(response) {
                            // alert('Status updated to ' + selectedStatus);
                            $('#statusModal').modal('hide');
                            setTimeout(() => {
                                location.reload()
                            }, 1500);
                            console.log(response)
                        },
                    });
                });
            });
        </script>
    @endhasrole
@endsection
@section('scripts')
@endsection
