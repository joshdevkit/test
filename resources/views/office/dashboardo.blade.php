@extends('layouts.officeadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-success">Dashboard</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @php
                $boxes = [
                'supplies' => 'Supplies',
                ];
                @endphp
                @foreach ($boxes as $key => $title)
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totals[$key] }}</h3>
                            <p>{{ $title }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clipboard"></i>
                        </div>
                        <a href="{{ url('/office/supplies') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach

                @php
                $boxes = [
                'equipment' => 'Equipments',
                ];
                @endphp
                @foreach ($boxes as $key => $title)
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totals[$key] }}</h3>
                            <p>{{ $title }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <a href="{{ url('/office/equipment') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach


            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Latest Transaction</h4>
                </div>
                <div class="card-body">
                    <table id="example1" class="table">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                            <tr>
                                {{-- <td>{{ $request->id }}</td>
                                <td>{{ $request->requested_by_name }}</td>
                                <td>{{ $request->equipment_item }}</td>
                                <td>{{ $request->quantity_requested }}</td>
                                <td>{{ $request->purpose }}</td>
                                <td>{{ $request->created_at }}</td>
                                <td>{{ $request->status }}</td>
                                <td>
                                    {{ now()->diffInDays($request->created_at) }}
                                </td> --}}
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->requested_by_name }}</td>
                                <td>
                                    @if ($request->item_type === 'Supplies')
                                    {{ $request->item_name }}
                                    @elseif ($request->item_type === 'Equipments')
                                    <button class="btn btn-secondary show-items" data-id="{{ $request->id }}">
                                        Show items
                                    </button>
                                    @endif
                                </td>
                                <td>{{ $request->quantity_requested }}</td>
                                <td>{{ $request->purpose }}</td>
                                <td>{{ date('F d, Y h:i A', strtotime($request->created_at)) }}</td>
                                <td>{{ $request->status }}</td>
                                <td>
                                    {{ $request->item_type === 'Equipments' ? now()->diffInDays($request->created_at) :
                                    'Not returnable'
                                    }}
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            Number of transactions (Site Office)
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <canvas id="pieChart2"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="itemsOnRequestEquipment" tabindex="-1" aria-labelledby="itemsOnRequestEquipmentLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemsOnRequestEquipmentLabel">Equipment Request Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group" id="items_display">
                </div>
            </div>
            <div class="modal-footer" id="items_button">
                <button type="button" class="btn btn-success d-none" id="submit-selected-items">Submit
                    Selected</button>
                <button type="button" class="btn btn-danger d-none" id="cancel-select-items">Cancel</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('buttons.js') }}"></script>

<script>
    $(document).ready(function() {
        let requestIds = [];
        $('#example1 tbody tr').each(function() {
            const row = $(this);
            const requestId = row.find('td:nth-child(1)').text().trim();
            const daysNotReturned = parseInt(row.find('td:nth-child(8)').text().trim(), 10);
            const status = row.find('td:nth-child(7)').text().trim();

            if (daysNotReturned >= 3 && status === 'Received') {
                requestIds.push(requestId);
                $.ajax({
                    url: '/office/notify-borrower',
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

        $(document).on('click', '.show-items', function() {
                $('#itemsOnRequestEquipment').modal('show')
                var requestId = $(this).data('id');
                $.ajax({
                    url: "{{ route('office-admin.transactions-details', ['']) }}/" + requestId,
                    method: 'GET',
                    data: {
                        id: requestId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        var itemsDisplay = $('#items_display');
                        itemsDisplay.empty();
                        $('#cancel-select-items').nextAll().remove();
                        if (response.length === 0) {
                            itemsDisplay.append(
                                '<div class="list-group-item text-center text-muted">No items found</div>'
                            );
                        } else {
                            const items = response;
                            let showSelectItemsGoodButton =
                                false;
                            let totalCountOfApprove = 0
                            let totalCountOfReceived = 0
                            let totalCountofApprove = 0
                            let totalCountofPending = 0
                            var itemsRequisitionId = items[0].id
                            let returnedItemsCount = 0
                            items.forEach(function(item) {
                                // console.log(item);

                                if (item.borrowed_equipment_status === "Pending") {
                                    totalCountofPending++
                                }
                                if (item.borrowed_equipment_status === "Approved") {
                                    totalCountOfApprove++;
                                }
                                if (item.borrowed_equipment_status === "Received") {
                                    totalCountOfReceived++
                                }

                                if (item.borrowed_equipment_status === "Returned") {
                                    returnedItemsCount++
                                }

                                var itemHtml = `
                                <div data-item-id=${item.equipment_serial_id} data-equipment-status=${item.equipment_status}
                                data-item-status=${item.borrowed_equipment_status} class="list-group-item d-flex justify-content-between
                                     align-items-center ${item.equipment_status === 'Queue' ? '' :
                                      (item.equipment_status === 'Good' ? 'bg-info' : 'bg-danger')}">
                                    <div class="item-details">
                                        <strong>Equipment Item:</strong> ${item.equipment_item} <br>
                                        <strong>Serial No:</strong> ${item.equipment_serial} - (<span class='text-black'>${item.borrowed_equipment_status}</span>)
                                    </div>
                                    `;


                                if (item.equipment_status != "Damaged") {
                                    itemHtml += `
                                <div class="ml-auto">
                                    <button class="btn btn-danger btn-sm mark-damaged
                                    ${item.borrowed_equipment_status != "Returned"  ? 'd-none': ''} "
                                    data-item-id="${item.equipment_serial_id}">
                                        <i class="fas fa-times-circle"></i> Damaged
                                    </button>
                                `;
                                }

                                if (item.equipment_notes == null) {
                                    itemHtml += `
                                        <button class="btn btn-info btn-sm add-note
                                        ${item.borrowed_equipment_status != "Returned"  ? 'd-none': ''}
                                        "
                                        data-item-id="${item.equipment_serial_id}">
                                            <i class="far fa-copy"></i> Add Notes
                                        </button>
                                        `;

                                    itemHtml += `</div>`;
                                }

                                itemHtml += '</div>';
                                $('#items_display').append(itemHtml);

                            });


                            actionButtons() //from public folder
                            $('#approve-selected-button').addClass('d-none')
                            if (totalCountofPending > 0) {
                                $('#submit-selected-button-approve').addClass('d-none')
                                $('#procedd-received-button').addClass('d-none')
                                $('#approve-selected-button').removeClass('d-none')
                            }

                            if (totalCountOfApprove > 1) {
                                $('#approve-selected-button').addClass('d-none')
                            }

                            if (totalCountOfReceived > 0) {
                                $('#approve-selected-button').addClass('d-none')
                                $('#submit-selected-button-approve').addClass('d-none')
                                $('#returned-all-button').removeClass('d-none')
                            }

                            if (returnedItemsCount > 0) {
                                $('#items_button').addClass('d-none')
                                // $('#submit-selected-button-approve').addClass('d-none')
                                // $('#approve-selected-button').addClass('d-none')
                            }
                            // Add checkboxes to items and show buttons on "Select Items to Approve"
                            $(document).on('click', '#approve-selected-button', function() {
                                $('#items_display .list-group-item').each(function() {
                                    // Only add checkboxes for items with "Pending" status
                                    if ($(this).data('item-status') ===
                                        'Pending' && $(this).data(
                                            'equipment-status') != 'Damaged' &&
                                        !$(this).find(
                                            'input[type="checkbox"]').length) {
                                        const itemId = $(this).data('item-id');
                                        const checkbox = $('<input>', {
                                            type: 'checkbox',
                                            class: 'item-checkbox mr-3',
                                            value: itemId,
                                        });

                                        $(this).prepend(checkbox);
                                    }
                                });

                                // Toggle button visibility
                                $('#approve-selected-button').addClass('d-none');
                                $('#submit-selected-button').removeClass('d-none');
                                $('#cancel-selected-button').removeClass('d-none');
                            });

                            // Remove checkboxes and toggle buttons on "Deselect / Cancel"
                            $(document).on('click', '#cancel-selected-button', function() {
                                $('#items_display .list-group-item').each(function() {
                                    // Remove checkboxes
                                    $(this).find('input[type="checkbox"]')
                                        .remove();
                                });

                                // Toggle button visibility
                                $('#cancel-selected-button').addClass('d-none');
                                $('#submit-selected-button').addClass('d-none');
                                $('#approve-selected-button').removeClass('d-none');
                            });

                            // Submit selected items on "Submit Selected"
                            $(document).on('click', '#submit-selected-button', function() {
                                let selectedItems = [];

                                $('#items_display .list-group-item input[type="checkbox"]:checked')
                                    .each(function() {
                                        // Get the parent list-group-item and then the data-item-id attribute
                                        let itemId = $(this).closest(
                                            '.list-group-item').data('item-id');
                                        selectedItems.push(
                                            itemId); // Push the item ID value
                                    });

                                $.ajax({
                                    url: '{{ route('office.approve-selected-items') }}',
                                    type: 'POST',
                                    data: {
                                        selectedItems: selectedItems,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        console.log(response);
                                        if (response.success) {
                                            location.reload()
                                        }
                                        $('#cancel-selected-button')
                                            .trigger('click');
                                    }
                                })

                            });


                            $(document).on('click', '#submit-selected-button-approve',
                                function() {
                                    $('#items_display .list-group-item').each(function() {
                                        // Only add checkboxes for items with "Pending" status
                                        if ($(this).data('item-status') ===
                                            'Approved' && $(this).data(
                                                'equipment-status') != 'Damaged' &&
                                            !$(this).find(
                                                'input[type="checkbox"]').length) {
                                            const itemId = $(this).data('item-id');
                                            const checkbox = $('<input>', {
                                                type: 'checkbox',
                                                class: 'item-checkbox mr-3',
                                                value: itemId,
                                            });

                                            $(this).prepend(checkbox);
                                        }
                                    });
                                    $(this).addClass('d-none')
                                    // $('#approve-selected-button').addClass('d-none');
                                    // $('#received-button').removeClass('d-none')
                                    $("#cancel-received-button").removeClass('d-none')
                                    $('#procedd-received-button').removeClass('d-none')
                                });




                            if (showSelectItemsGoodButton) {
                                const buttonHtml = `
                                        <button type="button" class="btn btn-primary" id="select-items-good">
                                            Select items you wish to mark as good
                                        </button>
                                    `;
                                $('#items_button').append(
                                    buttonHtml);
                            }



                            $('#select-items-good').on('click', function() {
                                $('.item-details').each(function(index, element) {
                                    const item = items[index];
                                    if (item.equipment_status !== 'Good') {
                                        const itemId = item.equipment_serial_id;
                                        $(element).prepend(`
                                            <input type="checkbox" class="item-checkbox" data-item-id="${itemId}" style="margin-right: 10px;">
                                        `);
                                    }
                                });
                                $(this).addClass('d-none');
                                $('#submit-selected-items').removeClass('d-none');
                                $('#cancel-select-items').removeClass('d-none');
                            });

                            $('#submit-selected-items').on('click', function() {
                                var selectedItems = [];
                                $('.item-checkbox:checked').each(function() {
                                    selectedItems.push($(this).data('item-id'));
                                });

                                $.ajax({
                                    url: '{{ route('office.submit-good-items') }}',
                                    method: 'POST',
                                    data: {
                                        selected_items: selectedItems,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.status == "success") {
                                            location.reload()
                                        }
                                    },
                                    error: function(response) {
                                        alert(
                                            'Failed to submit selected items.'
                                        );
                                    }
                                });
                            });

                            $('#procedd-received-button').on('click', function() {
                                var selectedItems = [];
                                $('#items_display .list-group-item input[type="checkbox"]:checked')
                                    .each(function() {
                                        selectedItems.push($(this)
                                            .val());
                                    });

                                $.ajax({
                                    url: '{{ route('office.mark-recieved-items') }}',
                                    method: 'POST',
                                    data: {
                                        selected_items: selectedItems,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            location.reload()
                                        }
                                    },
                                    error: function(response) {
                                        alert(
                                            'Failed to submit selected items.'
                                        );
                                    }
                                });

                            })

                            $('#cancel-select-items').on('click', function() {
                                $('.item-checkbox').remove();
                                $('#select-items-good').removeClass('d-none');
                                $('#submit-selected-items').addClass('d-none');
                                $('#cancel-select-items').addClass('d-none');
                            });

                            $('#cancel-received-button').on('click', function() {
                                $('.item-checkbox').remove();
                                $('#cancel-received-button').addClass('d-none')
                                $('#submit-selected-button-approve').removeClass(
                                    'd-none')
                                // $('#approve-selected-button').removeClass('d-none');
                                // $('#received-button').addClass('d-none')
                            });

                            $('#returned-all-button').on('click', function() {
                                $.ajax({
                                    url: '{{ route('office.returned-all') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        itemRequisitionId: itemsRequisitionId,
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            location.reload()
                                        }
                                    }
                                })
                            });
                        }
                    }


                });
            });
</script>



<script>
    $(document).ready(function() {

            $.ajax({
                url: "{{ route('site-office.chart') }}",
                method: 'GET',
                success: function(data) {

                    const labels = Object.keys(data);
                    const values = Object.values(data);
                    var ctx = $('#pieChart2')[0].getContext('2d');
                    var pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Requisitions by Category',
                                data: values,
                                backgroundColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem
                                                .raw;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
</script>
@endsection