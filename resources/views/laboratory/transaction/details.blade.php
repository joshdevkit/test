@extends('layouts.labadmin')

@section('content')
    <style>
        .signature-canvas {
            border: 1px solid #a7a4a4;
            border-radius: 0.7em;
            height: 150px;
            width: 100%;
            cursor: crosshair;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="text-success">Transactions / {{ $data->activity }} /
                            {{ $data->category->name }}</h2>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transactions</li>
                        </ol>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-black text-center">
                            This request was made at
                            {{ date('F d, Y h:i A', strtotime($data->date_time_filed)) }}

                        </h1>

                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-10 mt-3 col-md-12">
                            @php
                                $requisitionStatus = $data->status;
                            @endphp

                            @if ($requisitionStatus == 'Declined')
                                <p>This requisition has already been declined .</p>
                            @elseif($requisitionStatus === 'Pending')
                                <form action="{{ url('/laboratory/update-requisition-details/' . $data->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group" id="signt">
                                        <label for="signature">Signature</label>
                                        <canvas id="userSignature" class="signature-canvas"></canvas>
                                        <button type="button" class="btn btn-danger text-black btn-sm mt-2"
                                            onclick="clearSignature('#userSignature')">Clear</button>
                                        <input type="hidden" id="signature" name="signature">
                                    </div>

                                    <div id="reason" class="form-group d-none">
                                        <label for="">Feedback for Decline</label>
                                        <textarea name="feedback" rows="3" class="form-control"></textarea>
                                    </div>

                                    <div class="d-flex">
                                        <input type="hidden" name="category" value="{{ $data->category->id }}">
                                        <input type="hidden" name="requisition_id" id="requisition_id"
                                            value="{{ $data->id }}">
                                        <button type="submit"
                                            class="btn btn-success text-black mr-3 approve">Approve</button>
                                        <button type="button"
                                            class="btn btn-danger text-black mr-3 decline">Decline</button>
                                        <button type="submit"
                                            class="d-none btn btn-danger text-black mr-3 submit_decline">Decline</button>
                                    </div>
                                </form>
                            @elseif($requisitionStatus === 'Accepted by Dean')
                                <p>
                                    <a href="{{ route('laboratory.print-requisition', ['id' => $data->id]) }}"
                                        class="btn btn-link print_btn">This requisition has already
                                        been
                                        Accepted by
                                        Dean. Click here to Print</a>
                                </p>
                            @endif

                        </div>
                        <hr class="mb-5">
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Requisition Details</h4>
                                <hr>
                                <ul>
                                    <li><strong>Category:</strong> {{ $data->category->name }}</li>
                                    <li><strong>Date Filled:</strong>
                                        {{ date('F d, Y h:i A', strtotime($data->date_time_filed)) }}</li>
                                    <li><strong>Date Needed:</strong>
                                        {{ date('F d, Y h:i A', strtotime($data->date_time_needed)) }}</li>
                                    <li><strong>Instructor:</strong> {{ $data->instructor->name }}</li>
                                    <li><strong>Subject:</strong> {{ $data->subject }}</li>
                                    <li><strong>Course/Year:</strong> {{ $data->course_year }}</li>
                                    <li><strong>Activity:</strong> {{ $data->activity }}</li>
                                    <li><strong>Status:</strong> {{ $data->status }}</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4><strong>Students</strong></h4>
                                <hr>
                                @if ($data->students->isNotEmpty())
                                    <ol>
                                        @foreach ($data->students as $student)
                                            <li> {{ $student->student_name }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p>No students found.</p>
                                @endif
                            </div>
                        </div>
                        <a type="cancel" class="btn btn-danger float-right mt-5"
                            href="{{ url('/laboratory/transaction') }}">{{ __('Exit') }}</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        @if ($data->status != 'Declined')
                            <button id="select-items-button" class="btn btn-secondary d-none">Select items to
                                approve</button>
                        @endif
                        <button type="button" class="btn btn-primary mb-0 d-none" id="submit-button">Approve
                            Selected</button>
                        @if (
                            $data->items->pluck('serials')->flatten()->where('borrow_status', 'Approved')->isNotEmpty() &&
                                $data->labtext_signature != null)
                            @if ($data->dean_signature != null)
                                <button type="button" class="btn btn-success mb-0" id="item-received-button">Item
                                    Received</button>
                            @else
                                <span class="bg-warning text-white py-2 px-3 rounded-lg">Waiting for Dean approval</span>
                            @endif
                            {{-- @else
                            <span class="bg-warning text-white py-2 px-3 rounded-lg">Waiting to approve</span> --}}
                        @endif

                        @if ($data->items->pluck('serials')->flatten()->where('borrow_status', 'Received')->isNotEmpty())
                            <button type="button" class="btn btn-success mb-0" id="item-returned-button">Mark all as
                                Returned</button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="alert"></div>
                        @if ($data['items']->isNotEmpty())
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Equipment</th>
                                        <th>Description</th>
                                        <th>Brand</th>
                                        <th>Condition during borrow</th>
                                        <th>Item Status</th>
                                        <th>Product Serial</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->items as $item)
                                        @foreach ($item->serials as $serial)
                                            <tr
                                                class="{{ $serial->borrow_status === 'Pending' ? 'pending-item' : '' }}
                                                {{ $serial->borrow_status === 'Declined' ? 'bg-warning' : '' }}
                                                {{ $serial->borrow_status === 'Received' ? 'bg-success' : '' }}
                                                ">
                                                <td>
                                                    <input type="checkbox" class="item-checkbox d-none"
                                                        data-id="{{ $serial->id }}">
                                                </td>
                                                <td>{{ $serial->equipmentBelongs->equipment }}</td>
                                                <td>{{ $serial->serialRelatedItem->description }}</td>
                                                <td>{{ $serial->equipmentBelongs->brand }}</td>
                                                <td>{{ $serial->condition_during_borrow }}</td>
                                                <td>{{ $serial->serialRelatedItem->condition }}</td>
                                                <td>{{ $serial->serialRelatedItem->serial_no }}</td>
                                                <td>{{ $serial->borrow_status }}</td>
                                                <td>
                                                    @if ($serial->borrow_status === 'Returned' && $serial->serialRelatedItem->condition !== 'Damaged')
                                                        <button type="button" class="btn btn-danger mark-damaged"
                                                            data-id="{{ $serial->serialRelatedItem->id }}">
                                                            <i class="fas fa-times-circle"></i>
                                                            Damaged
                                                        </button>
                                                    @endif
                                                    @if (empty($serial->serialRelatedItem->notes) && $serial->borrow_status === 'Returned')
                                                        <button type="button" class="btn btn-primary add-notes"
                                                            data-note-id="{{ $serial->serialRelatedItem->id }}">
                                                            <i class="far fa-copy"></i>
                                                            Add Note
                                                        </button>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No items found.</p>
                        @endif
                    </div>
                </div>


            </div>
        </section>
    </div>

    <div class="modal fade" id="itemReceivedModal" tabindex="-1" role="dialog" aria-labelledby="itemReceivedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemReceivedModalLabel">Item Received</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Confirm that the item(s) have been received.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirm-receive">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemReturnedModal" tabindex="-1" role="dialog"
        aria-labelledby="itemReturnedModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemReturnedModalLabel">Mark all Borrowed Items Returned</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Confirm that the item(s) have been returned.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirm-returned">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="itemMarkAsDamageModal" tabindex="-1" role="dialog"
        aria-labelledby="itemMarkAsDamageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemMarkAsDamageModalLabel">Item Damaged</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Confirm that the item(s) have been damaged.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirm-damaged">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemAddNote" tabindex="-1" role="dialog" aria-labelledby="itemAddNoteLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemAddNoteLabel">Add Notes to current Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="confirm-notes">Confirm</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            setupSignatureCanvas('#userSignature', '#signature');
            $(document).on('click', '.decline', function() {
                $('.submit_decline').removeClass('d-none')
                $('.decline').addClass('d-none')
                $('#reason').removeClass('d-none')
                $('.approve').addClass('d-none')
                $('#signt').addClass('d-none')
            })
        });

        function setupSignatureCanvas(canvasId, inputId) {
            const $canvas = $(canvasId);
            const canvas = $canvas[0];
            const ctx = canvas.getContext('2d');
            let drawing = false;

            canvas.width = $canvas.width();
            canvas.height = $canvas.height();

            $canvas.on('mousedown touchstart', function(e) {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(getX(e), getY(e));
            });

            $canvas.on('mousemove touchmove', function(e) {
                if (drawing) {
                    ctx.lineTo(getX(e), getY(e));
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.stroke();
                }
            });

            $canvas.on('mouseup touchend', function() {
                drawing = false;
                ctx.closePath();
                saveSignature(canvasId, inputId);
            });

            $canvas.on('mouseleave touchcancel', function() {
                drawing = false;
            });

            function getX(event) {
                return (event.pageX || event.originalEvent.touches[0].pageX) - $canvas.offset().left;
            }

            function getY(event) {
                return (event.pageY || event.originalEvent.touches[0].pageY) - $canvas.offset().top;
            }
        }

        function clearSignature(canvasId) {
            const canvas = $(canvasId)[0];
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature(canvasId, inputId) {
            const canvas = $(canvasId)[0];
            const signatureData = canvas.toDataURL('image/png');
            $(inputId).val(signatureData);
        }



        //approve
        const $selectItemsButton = $('#select-items-button');
        const $submitButton = $('#submit-button');
        const $checkboxes = $('.item-checkbox');
        const $itemReceivedButton = $('#item-received-button');
        const $markAllReturnButton = $('#item-returned-button');
        let selectedIds = [];

        // Add Cancel button dynamically
        const $cancelButton = $('<button>', {
            id: 'cancel-button',
            class: 'btn btn-danger mb-0 d-none ml-3',
            text: 'Cancel',
        }).insertAfter($submitButton);

        const hasPending = $('#example1 .pending-item').length > 0;
        if (hasPending) {
            $selectItemsButton.removeClass('d-none');
        }

        // Show checkboxes and buttons when clicking "Select items to approve"
        $selectItemsButton.on('click', function() {
            $checkboxes.removeClass('d-none'); // Show checkboxes
            $submitButton.removeClass('d-none'); // Show "Approve Selected" button
            $cancelButton.removeClass('d-none'); // Show "Cancel" button
            $selectItemsButton.addClass('d-none'); // Hide "Select items to approve" button
        });

        // Handle checkbox selection
        $('#example1').on('change', '.item-checkbox', function() {
            const id = $(this).data('id');
            if (this.checked) {
                selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(selectedId => selectedId !== id);
            }
        });

        // Handle "Approve Selected" button
        $submitButton.on('click', function() {
            if (selectedIds.length === 0) {
                $('#alert')
                    .html(`
                    <div class='alert alert-warning alert-dismissible fade show text-white' role='alert'>
                        Please Select items first!
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                `)
                    .fadeIn();

                // Automatically fade out the alert after 1.5 seconds
                setTimeout(() => {
                    $('#alert .alert').fadeOut(500, function() {
                        $('#alert').empty(); // Clear the alert content after fadeOut
                    });
                }, 5500);

            }
            $.ajax({
                url: '{{ route('laboratory.approve-requisition-items') }}',
                type: 'POST',
                data: {
                    selectedIds: selectedIds,
                    requisitionId: '{{ $id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });

        // Handle "Cancel" button
        $cancelButton.on('click', function() {
            // Deselect all checkboxes
            $checkboxes.prop('checked', false).addClass('d-none');
            selectedIds = []; // Reset selected IDs
            $submitButton.addClass('d-none'); // Hide "Approve Selected" button
            $cancelButton.addClass('d-none'); // Hide "Cancel" button
            $selectItemsButton.removeClass('d-none'); // Show "Select items to approve" button
        });

        if ($('#example1 .bg-warning').length === 0) {
            $itemReceivedButton.removeClass('d-none');
        }

        $itemReceivedButton.on('click', function() {
            $('#itemReceivedModal').modal('show');
        });


        $('#confirm-receive').on('click', function() {
            $.ajax({
                url: '{{ route('laboratory.item-received') }}',
                type: 'POST',
                data: {
                    requisitionId: '{{ $id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        $('#itemReceivedModal').modal('hide');
                        location.reload();
                    }
                }
            });
        });

        $(document).on('click', '.mark-damaged', function() {
            var itemId = $(this).data('id')
            $('#itemMarkAsDamageModal').modal('show')
            triggerConfirm(itemId)
        })

        let selectedItemId

        $(document).on('click', '.add-notes', function() {
            var selectedId = $(this).data('note-id');
            $('#itemAddNote').modal('show');
            selectedItemId = selectedId
        });

        $(document).on('click', '#confirm-notes', function() {
            var notesContent = $('#notes').val();
            saveNotes(selectedItemId, notesContent);
        });

        function saveNotes(selectedItemId, notesContent) {
            $.ajax({
                url: '{{ route('laboratory.item-add-notes') }}',
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    notes: notesContent,
                    item_id: selectedItemId
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#itemAddNote').modal('hide')
                        location.reload()
                    }
                }
            })
        }

        function triggerConfirm(itemId) {
            $('#confirm-damaged').on('click', function() {
                $.ajax({
                    url: '{{ route('laboratory.item-damaged') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        selectedId: itemId
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            $('#itemMarkAsDamageModal').modal('hide')
                            location.reload()
                        }
                    }
                })
            })
        }

        $markAllReturnButton.on('click', function() {
            $('#itemReturnedModal').modal('show');
        });

        $('#confirm-returned').on('click', function() {
            $.ajax({
                url: '{{ route('laboratory.item-returned') }}',
                type: 'POST',
                data: {
                    requisitionId: '{{ $id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        $('#itemReturnedModal').modal('hide');
                        location.reload();
                    }
                }
            });
        });
    </script>
@endsection
