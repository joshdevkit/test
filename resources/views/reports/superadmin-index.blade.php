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
                let rows = [];
                document.querySelectorAll("#example1Reports tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });

                fetch("{{ route('print-lab-report') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'LABORATORY REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })


            $('#btn_print_all').on('click', function() {
                fetch("{{ route('print-Alllab-report') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            title: 'LABORATORY REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })


            $('#btn_print_requisition_report').on('click', function() {
                let rows = [];
                document.querySelectorAll("#requisition_table tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });

                fetch("{{ route('print-laboratory-requisition-reports') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'LABORATORY REQUISITION REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })

            $('#btn_print_requisition_all').on('click', function() {
                // Check if the DataTable is initialized
                fetch("{{ route('print-laboratory-requisition-reports-all') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            title: 'LABORATORY REQUISITION REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })


        });
</script>
@endsection