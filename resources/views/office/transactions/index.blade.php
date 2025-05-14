@extends('layouts.officeadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-success">Transactions</h1>
                </div>
                <div class="col-sm-6">

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
                            <a class="btn btn-primary btn-sm float-right"
                                href="{{ route('office.transaction-print') }}"> <i class="fas fa-print"></i> Print</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Instructor</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Purpose</th>
                                        <th>Datetime Borrowed</th>
                                        <th>Status</th>
                                        <th>Days Not Returned</th>
                                        <th>Datetime Returned</th>
                                        <th>Actions</th>
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
                                            {{ $request->item_type === 'Equipments' ?
                                            now()->diffInDays($request->created_at) : '' }}
                                        </td>
                                        <td>
                                            @if ($request->status === 'Returned')
                                            {{ date('F d, Y h:i A', strtotime($request->updated_at)) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($request->status === 'Pending' && $request->item_type === 'Supplies')
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> Actions
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item status-option" data-status="Approved"
                                                        data-id="{{ $request->id }}" href="#">Approved</a>
                                                    <a class="dropdown-item status-option" data-status="Declined"
                                                        data-id="{{ $request->id }}" href="#">Declined</a>
                                                </div>
                                            </div>
                                            @elseif ($request->status === 'Approved' && $request->item_type ===
                                            'Supplies')
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> Actions
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item status-option" data-status="Received"
                                                        data-id="{{ $request->id }}" href="#">Received</a>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
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

<!-- equipment request items -->
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('buttons.js') }}"></script>
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
                    url: "{{ url('site-office/transactions/update') }}",
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
        });

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

            $(document).on('click', '.add-note', function() {
                var itemId = $(this).data('item-id');
                var itemDiv = $(this).closest('.list-group-item');

                var notesHtml = `
                        <div class="form-group mt-2" id="note-added-section-${itemId}">
                            <label for="added-notes-${itemId}">Notes:</label>
                            <textarea class="form-control" id="added-notes-${itemId}" rows="3" placeholder="Enter notes..."></textarea>
                            <button class="btn btn-primary btn-sm mt-2 submit-added-notes" data-item-id="${itemId}">Submit</button>
                            <button class="btn btn-secondary btn-sm mt-2 cancel-added-notes" data-item-id="${itemId}">Cancel</button>
                        </div>
                    `;

                itemDiv.after(notesHtml);

                $(this).prop('disabled', true);
            });

            $(document).on('click', '.mark-damaged', function() {
                var itemId = $(this).data('item-id');
                var itemDiv = $(this).closest('.list-group-item');

                Swal.fire({
                    title: "Mark as Damaged?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Proceed!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('office.submit-as-damaged') }}',
                            method: "POST",
                            data: {
                                _token: '{{ csrf_token() }}',
                                item_id: itemId
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: "Success!",
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                        })
                    }
                });
            })

            $(document).on('click', '.cancel-added-notes', function() {
                var itemId = $(this).data('item-id');
                $(`#note-added-section-${itemId}`).remove();
                $(`button.add-note[data-item-id="${itemId}"]`).prop('disabled', false);
            });

            $(document).on('click', '.submit-added-notes', function() {
                var itemId = $(this).data('item-id');
                var notes = $(`#added-notes-${itemId}`).val();

                $.ajax({
                    url: '{{ route('office.submit-added-notes') }}',
                    method: 'POST',
                    data: {
                        item_id: itemId,
                        notes: notes,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Success",
                                text: response.message,
                                icon: "success"
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });


        });
</script>
@endsection