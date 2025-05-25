@extends('layouts.superadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-success">Surveying's List of Equipment</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('superadmin.surveying.create') }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-plus"></i> Add new items</a>
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
                                        <th>No.</th>
                                        <th>Equipment</th>
                                        <th>Description</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Date Acquired</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($surveyings as $surveying)
                                    <tr data-entry-id="{{ $surveying->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $surveying->equipment }}</td>
                                        <td>{{ $surveying->description }}</td>
                                        <td>{{ $surveying->brand }}</td>
                                        <td>{{ $surveying->quantity }}</td>
                                        <td>{{ $surveying->unit }}</td>
                                        <td>{{ $surveying->date_acquired }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                <a href="{{ route('superadmin.surveying.show', ['id' => $surveying->id]) }}"
                                                    class="btn btn-info">View More</a>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.surveying.edit', ['id' => $surveying->id]) }}">
                                                    <button class="btn btn-secondary">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </button>
                                                </form>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.surveying.destroy', ['id' => $surveying->id]) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
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
                        <h5><strong>INVENTORY OF SURVEYINGS</strong></h5>
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
                <h5><strong>INVENTORY OF TESTINGS</strong></h5>
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