@extends('layouts.superadmin')

@section('content')
    <div class="content-wrapper">
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
                        <h1 class="text-success">Reports</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <p class="text-muted">Select a report type to view detailed information.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <!-- Buttons to choose report -->
                        <div class="btn-group" role="group" aria-label="Report options">
                            <button type="button" class="btn btn-primary" id="btn_lost_damaged_report">Lost/Damaged
                                Equipment Report</button>
                            <button type="button" class="btn btn-secondary" id="btn_requisition_report">Requisition
                                Report</button>
                        </div>
                        <p class="text-muted mt-4">Choose an action whether Lost/Damaged Items from requisitions report or
                            All Requisitions.
                        </p>
                    </div>
                    <div class="card-body">
                        <!-- Lost/Damaged Equipment Report Section -->
                        <div id="lost_damaged_report_section" class="d-none mt-4">
                            <h4>Lost/Damaged Equipment Report</h4>
                            <p class="text-muted">Choose a reporting period and specify the dates to generate the report.
                            </p>

                            <!-- Action buttons -->
                            <div class="d-flex mb-3 mt-4">
                                <button class="btn btn-success" id="btn_weekly_report">Weekly</button>
                                <button class="btn btn-info mx-2" id="btn_monthly_report">Monthly</button>
                                <div class="ml-auto">
                                    <button class="btn btn-info" id="btn_print_report">Print</button>
                                    <button class="btn btn-warning ml-2" id="btn_print_all">Print All</button>
                                </div>
                            </div>
                            <p class="text-muted mt-3">You can print the current report or all available reports.</p>

                            <!-- Date selection -->
                            <div id="weekly_section" class="d-none mb-3 row align-items-center">
                                <div class="col-auto">
                                    <label>From:</label>
                                    <input type="date" class="form-control" id="weekly_from">
                                </div>
                                <div class="col-auto">
                                    <label>To:</label>
                                    <input type="date" class="form-control" id="weekly_to">
                                </div>
                            </div>

                            <div id="monthly_section" class="d-none mb-3">
                                <label>Select Month:</label>
                                <select class="form-control" id="monthly_select">
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>



                            <!-- Report table -->
                            <table id="example1Reports" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Equipment Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Date Reported</th>
                                    </tr>
                                </thead>
                                <tbody id="data_tbody">

                                </tbody>
                            </table>


                        </div>

                        <!-- Requisition Report Section -->
                        <div id="requisition_report_section" class="d-none mt-4">
                            <h4>Requisition Report</h4>
                            <p class="text-muted">Select a reporting option to view the requisition report.</p>

                            <div class="d-flex mb-3 mt-4">
                                <button type="button" class="btn btn-success"
                                    id="btn_weekly_requisition_report">Weekly</button>
                                <button type="button" class="btn btn-info mx-2"
                                    id="btn_monthly_requisition_report">Monthly</button>
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-info"
                                        id="btn_print_requisition_report">Print</button>
                                    <button type="button" class="btn btn-warning ml-2" id="btn_print_requisition_all">Print
                                        All</button>
                                </div>
                            </div>
                            <p class="text-muted mt-3">You can print the current report or all available reports.</p>

                            <!-- Date selection -->
                            <div id="weekly_requisition_section" class="d-none mb-3 row align-items-center">
                                <div class="col-auto">
                                    <label>From:</label>
                                    <input type="date" class="form-control" id="weekly_requisition_from">
                                </div>
                                <div class="col-auto">
                                    <label>To:</label>
                                    <input type="date" class="form-control" id="weekly_requisiton_to">
                                </div>
                            </div>

                            <div id="monthly_requisition_section" class="d-none mb-3">
                                <label>Select Month:</label>
                                <select class="form-control" id="monthly_requisition_select">
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>

                            <table id="requisition_table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Activity</th>
                                        <th>Category</th>
                                        <th>Equipment/Item/Serials</th>
                                        <th>Course Year</th>
                                        <th>Instructor</th>
                                        <th>Students</th>
                                        <th>Status</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="requisition_tbody">
                                </tbody>
                            </table>
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
            let originalData = [];

            $('#btn_lost_damaged_report').click(function() {
                resetFilters();
                $('#lost_damaged_report_section').removeClass('d-none');
                $('#requisition_report_section').addClass('d-none');

                $.ajax({
                    url: '{{ route('auth.filter-reports') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filter: "lost_damaged"
                    },
                    success: function(response) {
                        console.log(response);
                        $('#example1').empty();
                        if (response.damage_lost_items) {
                            originalData = response.items;
                            populateTable(originalData);
                        }
                    }
                });
            });

            $('#btn_weekly_report').click(function() {
                $('#weekly_section').removeClass('d-none');
                $('#monthly_section').addClass('d-none');
            });

            $('#btn_monthly_report').click(function() {
                $('#monthly_section').removeClass('d-none');
                $('#weekly_section').addClass('d-none');
            });

            // Filter and display weekly report
            $('#weekly_to').on('change', function() {
                let fromDate = new Date($('#weekly_from').val());
                let toDate = new Date($('#weekly_to').val());
                let filteredData = originalData.filter(function(item) {
                    let reportedDate = new Date(item.serial_related_item.noted_at);
                    return reportedDate >= fromDate && reportedDate <= toDate;
                });
                populateTable(filteredData);
            });

            // Filter and display monthly report
            $('#monthly_select').on('change', function() {
                let selectedMonth = $('#monthly_select').val();
                let filteredData = originalData.filter(function(item) {
                    let reportedDate = new Date(item.serial_related_item.noted_at);
                    return reportedDate.getMonth() + 1 === parseInt(selectedMonth);
                });
                populateTable(filteredData);
            });

            function resetFilters() {
                $('#weekly_from').val('');
                $('#weekly_to').val('');
                $('#monthly_select').val('');
                $('#weekly_section').addClass('d-none');
                $('#monthly_section').addClass('d-none');
            }

            function populateTable(data) {
                let tableData = data.map(function(item) {
                    let formattedDate = new Date(item.serial_related_item.noted_at).toLocaleString(
                        'en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });

                    return [
                        item.equipment_belongs.equipment + " - Serial: " + item.serial_related_item
                        .serial_no,
                        item.equipment_belongs.category.name,
                        item.borrow_status,
                        item.serial_related_item.notes,
                        formattedDate
                    ];
                });

                $('#example1Reports').DataTable({
                    data: tableData,
                    columns: [{
                            title: "Equipment Name"
                        },
                        {
                            title: "Category"
                        },
                        {
                            title: "Status"
                        },
                        {
                            title: "Notes"
                        },
                        {
                            title: "Date Reported"
                        }
                    ],
                    destroy: true,
                    responsive: true
                });
            }


            //Requisition
            $('#btn_requisition_report').click(function() {
                resetFilters();
                $('#requisition_report_section').removeClass('d-none');
                $('#lost_damaged_report_section').addClass('d-none');

                $.ajax({
                    url: '{{ route('auth.filter-reports') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filter: "requisition"
                    },
                    success: function(response) {
                        console.log(response);
                        populateRequisitionTable(response);
                    }
                });
            });


            $('#btn_weekly_requisition_report').click(function() {
                $('#weekly_requisition_section').removeClass('d-none');
                $('#monthly_requisition_section').addClass('d-none');
            });

            // Show Monthly Filter
            $('#btn_monthly_requisition_report').click(function() {
                $('#monthly_requisition_section').removeClass('d-none');
                $('#weekly_requisition_section').addClass('d-none');
            });

            // Weekly Filter Logic
            $('#weekly_requisition_from, #weekly_requisiton_to').change(function() {
                let fromDate = $('#weekly_requisition_from').val();
                let toDate = $('#weekly_requisiton_to').val();
                filterRequisitionTableByDate(fromDate, toDate);
            });

            // Monthly Filter Logic
            $('#monthly_requisition_select').change(function() {
                let selectedMonth = $(this).val();
                filterRequisitionTableByMonth(selectedMonth);
            });

            // Function to filter table data based on date range
            function filterRequisitionTableByDate(fromDate, toDate) {
                $('#requisition_table').DataTable().rows().every(function() {
                    let row = this.data();
                    let rowDate = new Date(row[8]); // Date column index

                    if (fromDate && toDate) {
                        let from = new Date(fromDate);
                        let to = new Date(toDate);
                        if (rowDate >= from && rowDate <= to) {
                            $(this.node()).show();
                        } else {
                            $(this.node()).hide();
                        }
                    } else {
                        $(this.node()).show();
                    }
                });
            }

            // Function to filter table data based on selected month
            function filterRequisitionTableByMonth(selectedMonth) {
                $('#requisition_table').DataTable().rows().every(function() {
                    let row = this.data();
                    let rowDate = new Date(row[8]); // Date column index
                    let rowMonth = ('0' + (rowDate.getMonth() + 1)).slice(-2); // Format as "MM"

                    if (selectedMonth === rowMonth) {
                        $(this.node()).show();
                    } else {
                        $(this.node()).hide();
                    }
                });
            }

            function populateRequisitionTable(data) {
                let tableData = data.map(function(item) {
                    let studentNames = '<ul>';
                    item.students.forEach(function(student) {
                        studentNames += '<li>' + student.student_name + '</li>';
                    });
                    studentNames += '</ul>';
                    let equipmentDetails = '<ul>';
                    item.items.forEach(function(requisitionItem) {
                        requisitionItem.serials.forEach(function(serial) {
                            equipmentDetails +=
                                `<li>${serial.equipment_belongs.equipment} - ${serial.serial_related_item.serial_no}</li>`;
                        });
                    });
                    equipmentDetails += '</ul>';
                    let formattedDate = new Date(item.created_at).toLocaleString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });

                    return [
                        item.activity,
                        item.category.name,
                        equipmentDetails,
                        item.course_year,
                        item.instructor.name,
                        studentNames,
                        item.status,
                        item.subject,
                        formattedDate // Updated formatted date
                    ];
                });

                $('#requisition_table').DataTable({
                    data: tableData,
                    columns: [{
                            title: "Activity"
                        },
                        {
                            title: "Category"
                        },
                        {
                            title: "Equipment/Item"
                        },
                        {
                            title: "Course Year"
                        },
                        {
                            title: "Instructor"
                        },
                        {
                            title: "Students"
                        },
                        {
                            title: "Status"
                        },
                        {
                            title: "Subject"
                        },
                        {
                            title: "Date"
                        }
                    ],
                    destroy: true,
                    responsive: true
                });
            }



            //print lost_damaged

            $('#btn_print_report').on('click', function() {
                let dataTable = document.querySelector('#example1Reports').cloneNode(true);
                let theadRow = dataTable.querySelector('thead tr');
                let tbodyRows = dataTable.querySelectorAll('tbody tr');

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
                        <h5><strong>LABORATORY TRANSACTION</strong></h5>
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
            })

            $('#btn_print_all').on('click', function() {
                // Check if the DataTable is initialized
                var table = $('#example1Reports').DataTable();

                if (table) {
                    // Destroy the DataTable instance before printing
                    table.destroy();
                }

                // Now remove the 'id' and any other DataTable related properties
                var tableElement = $('#example1Reports'); // Get the table element itself
                tableElement.removeAttr('id').removeClass('dataTable');

                // Clone the table (without DataTable functionalities)
                var dataTableClone = tableElement.clone(true,
                    true); // Clone with all child elements and events

                // Remove the last child of the header row
                let theadRow = dataTableClone.find('thead tr'); // Get the header row from the cloned table

                // Remove the last child from each row in the tbody
                let tbodyRows = dataTableClone.find(
                    'tbody tr'); // Get all rows from the tbody of the cloned table
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
            })


            //requisition
            $('#btn_print_requisition_report').on('click', function() {
                let dataTable = document.querySelector('#requisition_table').cloneNode(true);
                let theadRow = dataTable.querySelector('thead tr');
                let tbodyRows = dataTable.querySelectorAll('tbody tr');

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
                        <h5><strong>LABORATORY REQUISITION REPORTS</strong></h5>
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
            })

            $('#btn_print_requisition_all').on('click', function() {
                // Check if the DataTable is initialized
                var table = $('#requisition_table').DataTable();

                if (table) {
                    // Destroy the DataTable instance before printing
                    table.destroy();
                }

                // Now remove the 'id' and any other DataTable related properties
                var tableElement = $('#requisition_table'); // Get the table element itself
                tableElement.removeAttr('id').removeClass('dataTable');

                // Clone the table (without DataTable functionalities)
                var dataTableClone = tableElement.clone(true,
                    true); // Clone with all child elements and events

                // Remove the last child of the header row
                let theadRow = dataTableClone.find('thead tr'); // Get the header row from the cloned table

                // Remove the last child from each row in the tbody
                let tbodyRows = dataTableClone.find(
                    'tbody tr'); // Get all rows from the tbody of the cloned table
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
            })

        });
    </script>
@endsection
