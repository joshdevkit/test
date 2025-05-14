@extends('layouts.superadmin')

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
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Equipment</th>
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
                                            @foreach ($requisition->items as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $requisition->instructor->name }}</td>
                                                    <td>
                                                        @foreach ($item->serials as $serial)
                                                            {{ $serial->serialRelatedItem->serial_no }} -
                                                            {{ $serial->equipmentBelongs->equipment }}<br>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        {{ $requisition->activity }}
                                                    </td>
                                                    <td>{{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                                                    </td>
                                                    <td>{{ $requisition->status }}
                                                    </td>
                                                    <td>
                                                        {{ $requisition->status === 'Returned' ? '' : \Carbon\Carbon::parse($requisition->date_time_filed)->diffInDays(now()) . ' days' }}

                                                    </td>
                                                    <td>
                                                        {{ $requisition->status === 'Returned' ? date('F d, Y h:i A', strtotime($requisition->returned_date)) : '' }}
                                                    </td>
                                                    @hasrole('laboratory')
                                                        <td class="d-flex flex-auto">
                                                            @if ($requisition->status != 'Approved and Prepared')
                                                                <div class="dropdown">
                                                                    <a class="btn btn-secondary dropdown-toggle" href="#"
                                                                        role="button" data-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        <i class="fas fa-cog"></i> Configure
                                                                    </a>
                                                                    <div class="dropdown-menu">
                                                                        @if ($requisition->status === 'Pending')
                                                                            <a class="dropdown-item status-option"
                                                                                data-status="Approved"
                                                                                data-id="{{ $requisition->id }}"
                                                                                href="{{ route('borrows.show', ['id' => $requisition->id]) }}">Approved</a>
                                                                            <a class="dropdown-item status-option"
                                                                                data-status="Declined"
                                                                                data-id="{{ $requisition->id }}"
                                                                                href="{{ route('borrows.show', ['id' => $requisition->id]) }}">Declined</a>
                                                                        @elseif ($requisition->status === 'Approved')
                                                                            <a class="dropdown-item status-option"
                                                                                data-status="Received"
                                                                                data-id="{{ $requisition->id }}"
                                                                                href="#">Received</a>
                                                                        @elseif($requisition->status == 'Accepted by Dean')
                                                                            <a class="dropdown-item status-option"
                                                                                data-status="Returned"
                                                                                data-id="{{ $requisition->id }}"
                                                                                href="#">Returned</a>
                                                                            <a class="dropdown-item status-option"
                                                                                data-status="Damaged"
                                                                                data-id="{{ $requisition->id }}"
                                                                                href="#">Damaged</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        @elsehasrole('superadmin')
                                                    @endhasrole
                                                </tr>
                                            @endforeach
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
@endsection
@section('scripts')
    <script>
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
                        <h5><strong>LABORATORY TRANSACTIONS</strong></h5>
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
                <h5><strong>LABORATORY TRANSACTIONS</strong></h5>
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
    </script>
@endsection
