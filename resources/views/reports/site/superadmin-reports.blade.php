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
                    <div class="btn-group" role="group" aria-label="Report options">
                        <button type="button" class="btn btn-primary" id="lost_damage">Lost/Damaged
                            Equipment</button>
                        <button type="button" class="btn btn-success" id="equipment_report">Equipment</button>
                        <button type="button" class="btn btn-secondary" id="supplies_report">Supplies</button>
                    </div>
                    <p class="text-muted mt-4">Choose an action whether reports of equipment Items or supplies.
                    </p>
                </div>
                <div class="card-body">
                    <div id="lost_damage_section" class="d-none mt-4">
                        <h4>Lost/Damage Equipment Report</h4>
                        <p class="text-muted">Choose a reporting period and specify the dates to generate the report.
                        </p>

                        <div class="d-flex mb-3 mt-4">
                            <div class="ml-auto">
                                <button class="btn btn-info" id="btn_print_lost_damager_report">Print</button>
                                <button class="btn btn-warning ml-2" id="btn_print_lost_damager_all">Print All</button>
                            </div>
                        </div>

                        <p class="text-muted mt-3">You can print the current report or all available reports.</p>

                        <table id="DamagedEquipmentTable" class="table table-bordered table-striped mt-5">
                            <thead>
                                <tr>
                                    <th class="w-25">ITEM</th>
                                    <th>REQUESTED BY</th>
                                    <th>PURPOSE</th>
                                    <th>STATUS</th>
                                    <th>NOTES</th>
                                    <th>DATE REQUESTED</th>
                                </tr>
                            </thead>
                            <tbody id="data_lost_damage_equipment">

                            </tbody>
                        </table>
                    </div>

                    <div id="equipment_section" class="d-none mt-4">
                        <h4>Equipment Report</h4>
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

                        <table id="equipmentTable" class="table table-bordered table-striped mt-5">
                            <thead>
                                <tr>
                                    <th class="w-25">ITEM</th>
                                    <th>REQUESTED BY</th>
                                    <th>PURPOSE</th>
                                    <th>STATUS</th>
                                    <th>NOTES</th>
                                    <th>DATE REQUESTED</th>
                                </tr>
                            </thead>
                            <tbody id="data_equipment">

                            </tbody>
                        </table>
                    </div>

                    <div id="supplies_section" class="d-none mt-4">
                        <h4>Supplies Report</h4>
                        <p class="text-muted">Choose a reporting period and specify the dates to generate the report.
                        </p>

                        <div class="d-flex mb-3 mt-4">
                            <button class="btn btn-success" id="btn_supplies_weekly_report">Weekly</button>
                            <button class="btn btn-info mx-2" id="btn_supplies_monthly_report">Monthly</button>
                            <div class="ml-auto">
                                <button class="btn btn-info" id="btn_supplies_print_report">Print</button>
                                <button class="btn btn-warning ml-2" id="btn_supplies_print_all">Print All</button>
                            </div>
                        </div>

                        <p class="text-muted mt-3">You can print the current report or all available reports.</p>

                        <div id="weekly_supplies_section" class="d-none mb-3 row align-items-center">
                            <div class="col-auto">
                                <label>From:</label>
                                <input type="date" class="form-control" id="weekly_supplies_from">
                            </div>
                            <div class="col-auto">
                                <label>To:</label>
                                <input type="date" class="form-control" id="weekly_supplies_to">
                            </div>
                        </div>

                        <div id="monthly_supplies_section" class="d-none mb-3">
                            <label>Select Month:</label>
                            <select class="form-control" id="monthly_supplies_select">
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

                        <table id="suppliesTable" class="table table-bordered table-striped mt-5">
                            <thead>
                                <tr>
                                    <th class="w-25">ITEM</th>
                                    <th>QUANTITY REQUESTED</th>
                                    <th>REQUESTED BY</th>
                                    <th>PURPOSE</th>
                                    <th>DATE REQUESTED</th>
                                </tr>
                            </thead>
                            <tbody id="data_supplies">

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
            let originalSuppliesData = [];

            $('#equipment_report').click(function() {
                $('#equipment_section').removeClass('d-none');
                $('#lost_damage_section').addClass('d-none')
                $("#supplies_section").addClass('d-none')
                $.ajax({
                    url: '{{ route('auth.site-filter-reports') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filterType: "equipment_requisition_all",
                    },
                    success: function(response) {
                        console.log(response);
                        originalData = response;
                        populateEquipmentTable(response);
                        $('#equipmentTable').DataTable();
                    }
                });
            });

            $('#lost_damage').click(function() {
                $('#lost_damage_section').removeClass('d-none')
                $('#equipment_section').addClass('d-none');
                $("#supplies_section").addClass('d-none')
                $.ajax({
                    url: '{{ route('auth.site-filter-reports') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filterType: "equipment",
                    },
                    success: function(response) {
                        console.log(response);
                        originalData = response;
                        loadLostDamageTable(response);
                        $('#DamagedEquipmentTable').DataTable();
                    }
                });
            });

             function loadLostDamageTable(data) {
                let tableBody = $('#data_lost_damage_equipment')
                tableBody.empty();
                console.log(data);
                data.forEach(function(item) {
                    let formattedDate = new Date(item.date_added).toLocaleString();

                    let tableRow = `<tr>
                                <td>Item: ${item.equipment_item} - Serial No: ${item.equipment_serial_no}</td>
                                <td>${item.request_by}</td>
                                <td>${item.purpose}</td>
                                <td>${item.item_status}</td>
                                <td>${item.equipment_notes ?? ''}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                    tableBody.append(tableRow);
                });
            }

            $('#btn_weekly_report').click(function() {
                $('#weekly_section').removeClass('d-none');
                $('#monthly_section').addClass('d-none');
            });

            $('#btn_monthly_report').click(function() {
                $('#monthly_section').removeClass('d-none');
                $('#weekly_section').addClass('d-none');
            });

            $('#weekly_from, #weekly_to').on('change', function() {
                filterEquipmentTable(originalData); // Pass the originalData to the filter function
            });

            $('#monthly_select').on('change', function() {
                filterEquipmentTable(originalData); // Pass the originalData to the filter function
            });

            function populateEquipmentTable(data) {
                let tableBody = $('#data_equipment');
                tableBody.empty();

                data.forEach(function(item) {
                    let formattedDate = new Date(item.date_added).toLocaleString();

                    let tableRow = `<tr>
                                <td>Item: ${item.equipment_item} - Serial No: ${item.serial_numbers}</td>
                                <td>${item.request_by}</td>
                                <td>${item.purpose}</td>
                                <td>${item.borrow_status}</td>
                                <td>${item.equipment_notes ?? ''}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                    tableBody.append(tableRow);
                });
            }

            function filterEquipmentTable(data) {
                let fromDate = new Date($('#weekly_from').val());
                let toDate = new Date($('#weekly_to').val());
                let selectedMonth = $('#monthly_select').val();
                let filteredData = [];

                if ($('#weekly_section').is(':visible')) {
                    filteredData = data.filter(function(item) {
                        let itemDate = new Date(item.date_added);
                        return itemDate >= fromDate && itemDate <= toDate;
                    });
                } else if ($('#monthly_section').is(':visible')) {
                    filteredData = data.filter(function(item) {
                        let itemDate = new Date(item.date_added);
                        return itemDate.getMonth() + 1 == selectedMonth;
                    });
                }

                populateEquipmentTable(filteredData);
            }

            function populateSuppliesTable(data) {
                let tableBody = $('#data_supplies');
                tableBody.empty();

                data.forEach(function(item) {
                    let formattedDate = new Date(item.date_added).toLocaleString();

                    let tableRow = `<tr>
                                <td>Item: ${item.item}</td>
                                <td>${item.quantity_requested}</td>
                                <td>${item.request_by}</td>
                                <td>${item.purpose}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                    tableBody.append(tableRow);
                });
            }

            function resetFilters() {
                $('#weekly_from').val('');
                $('#weekly_to').val('');
                $('#monthly_select').val('');
                $('#weekly_section').addClass('d-none');
                $('#monthly_section').addClass('d-none');
            }

            $('#supplies_report').click(function() {
                resetFilters();
                $('#supplies_section').removeClass('d-none');
                $('#equipment_section').addClass('d-none');

                $.ajax({
                    url: '{{ route('auth.site-filter-reports') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        filterType: "supplies",
                    },
                    success: function(response) {
                        console.log(response);
                        originalSuppliesData = response;
                        populateSuppliesTable(response);
                        $('#suppliesTable').DataTable();
                    }
                });
            });

            $('#btn_supplies_weekly_report').click(function() {
                $('#weekly_supplies_section').removeClass('d-none');
                $('#monthly_supplies_section').addClass('d-none');
            });

            $('#btn_supplies_monthly_report').click(function() {
                $('#monthly_supplies_section').removeClass('d-none');
                $('#weekly_supplies_section').addClass('d-none');
            });

            $('#weekly_supplies_from, #weekly_supplies_to').on('change', function() {
                filterSuppliesTable(originalSuppliesData);
            });

            $('#monthly_supplies_select').on('change', function() {
                filterSuppliesTable(originalSuppliesData);
            });

            function populateSuppliesTable(data) {
                let tableBody = $('#data_supplies');
                tableBody.empty();

                data.forEach(function(item) {
                    let formattedDate = new Date(item.date_added).toLocaleString();

                    let tableRow = `<tr>
                                <td>${item.item}</td>
                                <td>${item.quantity_requested}</td>
                                <td>${item.request_by}</td>
                                <td>${item.purpose}</td>
                                <td>${formattedDate}</td>
                            </tr>`;
                    tableBody.append(tableRow);
                });
            }

            function filterSuppliesTable(data) {
                let fromDate = new Date($('#weekly_supplies_from').val());
                let toDate = new Date($('#weekly_supplies_to').val());
                let selectedMonth = $('#monthly_supplies_select').val();
                let filteredData = [];

                if ($('#weekly_supplies_section').is(':visible')) {
                    filteredData = data.filter(function(item) {
                        let itemDate = new Date(item.date_added);
                        return itemDate >= fromDate && itemDate <= toDate;
                    });
                } else if ($('#monthly_supplies_section').is(':visible')) {
                    filteredData = data.filter(function(item) {
                        let itemDate = new Date(item.date_added);
                        return itemDate.getMonth() + 1 == selectedMonth;
                    });
                }

                populateSuppliesTable(filteredData);
            }

            //print EQUIPMENT REPORTS
            $('#btn_print_report').on('click', function() {
                let rows = [];
                document.querySelectorAll("#equipmentTable tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });

                fetch("{{ route('print-site-equipment-reports') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'SITE OFFICE EQUIPMENT'
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
                let rows = [];
                document.querySelectorAll("#equipmentTable tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });


                fetch("{{ route('print-all-equipments-reports-admin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'SITE OFFICE EQUIPMENT REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })

            //LOST
            $('#btn_print_lost_damager_report').click(function(){
                let rows = [];
                document.querySelectorAll("#DamagedEquipmentTable tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });

                fetch("{{ route('print-all-damged-reports-admin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'SITE OFFICE LOST/DAMAGED EQUIPMENT REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })

            $('#btn_print_lost_damager_all').click(() => {
                const table = $('#DamagedEquipmentTable').DataTable();

                const currentPageLength = table.page.len();

                table.page.len(-1).draw();

                setTimeout(() => {
                    let rows = [];
                    document.querySelectorAll("#DamagedEquipmentTable tbody tr").forEach((row) => {
                        let rowData = [];
                        row.querySelectorAll("td").forEach((td) => {
                            rowData.push(td.innerText.trim());
                        });
                        rows.push(rowData);
                    });

                    fetch("{{ route('print-damged-reports-admin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'SITE OFFICE LOST/DAMAGED EQUIPMENT REPORTS'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error))
                    .finally(() => {

                        table.page.len(currentPageLength).draw();
                    });
                }, 500);
            });

            //PRINT SUPPLIES REPORTS
            //btn_supplies_print_report
            //btn_supplies_print_all

            $('#btn_supplies_print_report').on('click', function() {
                // var suppliesTable
                //
                let rows = [];
                document.querySelectorAll("#suppliesTable tbody tr").forEach((row) => {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        rowData.push(td.innerText.trim());
                    });
                    rows.push(rowData);
                });

                fetch("{{ route('suppliesReportPrintbtn') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            data: rows,
                            title: 'SITE OFFICE SUPPLIES'
                        }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error));
            })

            $('#btn_supplies_print_all').on('click', function() {
                //
                fetch("{{ route('suppliesReportPrintAllbtn') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            title: 'SITE OFFICE SUPPLIES'
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