@extends('layouts.userapp')

@section('content')
    <style>
        .signature-canvas {
            border: 1px solid #000;
            width: 100%;
            height: 150px;
            cursor: crosshair;
        }
    </style>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-success">Request</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title">Laboratory Requisition</h1>
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
                    <div class="row">
                        <div class="col-md-12">
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

                            <form id="category-form" method="POST" action="{{ route('teachersborrow.selectCategory') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category"
                                        onchange="this.form.submit()">
                                        <option value="">Select a category</option>
                                        <option value="General Construction"
                                            {{ session('category') == 'General Construction' ? 'selected' : '' }}>
                                            Construction</option>
                                        <option value="Testing & Mechanics"
                                            {{ session('category') == 'Testing & Mechanics' ? 'selected' : '' }}>
                                            Testings
                                        </option>
                                        <option value="Surveying"
                                            {{ session('category') == 'Surveying' ? 'selected' : '' }}>
                                            Surveyings</option>
                                        <option value="Hydraulics and Fluids"
                                            {{ session('category') == 'Hydraulics and Fluids' ? 'selected' : '' }}>
                                            Fluid
                                        </option>
                                        <option value="Computer Engineering"
                                            {{ session('category') == 'Computer Engineering' ? 'selected' : '' }}>
                                            Computer Engineering</option>
                                    </select>
                                </div>
                            </form>

                            @if (session('category'))
                                <form id="submitForm" action="{{ route('teachersborrow.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ session('category') }}">
                                    <div class="form-row mb-3">
                                        <div class="col">
                                            <label for="dateFiled">Date & Time Filed:</label>
                                            <input type="text" class="form-control" id="dateFiled" name="dateFiled"
                                                value="{{ \Carbon\Carbon::now() }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="dateNeeded">Date & Time Needed:</label>
                                            <input type="datetime-local" class="form-control validate" id="dateNeeded"
                                                name="dateNeeded">
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col">
                                            <label for="instructor">Instructor:</label>
                                            <input type="text" class="form-control validate" id="instructor"
                                                name="instructor" value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="subject">Subject:</label>
                                            <input type="text" class="form-control validate" id="subject"
                                                name="subject">
                                        </div>
                                        <div class="col">
                                            <label for="courseYear">Course & Year</label>
                                            <select class="form-control validate" id="courseYear" name="courseYear">
                                                <option value="">Select Course</option>
                                                <option value="BSCE">BSCE</option>
                                                <option value="BSCPE">BSCPE</option>
                                                <option value="BSENSE">BSENSE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="activityTitle">Title of Activity:</label>
                                        <input type="text" class="form-control validate" id="activityTitle"
                                            name="activityTitle">
                                    </div>

                                    <div id="items">
                                        <div class="item-row form-row mb-3">
                                            <div class="col col-2">
                                                <label for="item-0">Equipment</label>
                                                <select class="form-control validate item-select" id="item-0"
                                                    name="items[0][item_id]">
                                                    <option value="">Select an item</option>
                                                    @foreach (session('items') as $item)
                                                        <option value="{{ $item['id'] }}"
                                                            data-brand="{{ $item['brand'] }}"
                                                            data-quantity="{{ $item['count'] }}">
                                                            {{ $item['equipment'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col col-4">
                                                <label for="serial-0">Items</label>
                                                <select class="form-control validate serial-select" id="serial-0"
                                                    name="items[0][items][]" multiple>
                                                    <option value="">Select Serial</option>
                                                </select>
                                            </div>

                                            <div class="col">
                                                <label for="qty-0">Quantity:</label>
                                                <input readonly type="number"
                                                    class="form-control validate quantity-input" id="qty-0"
                                                    name="items[0][quantity]" data-max-quantity="0">
                                            </div>
                                            <div class="col">
                                                <label for="brand-0">Brand:</label>
                                                <input type="text" class="form-control validate" id="brand-0"
                                                    name="items[0][brand]" readonly>
                                            </div>
                                            <div class="col">
                                                <label for="remarks-0">Condition:</label>
                                                <input type="text" class="form-control validate" id="remarks-0"
                                                    name="items[0][remarks]" placeholder="Enter condition">
                                            </div>
                                            <input type="hidden" id="item-id-0" name="items[0][item_id]"
                                                value="">
                                            <div class="col-md-3 form-group align-self-end absolute"
                                                style="margin-top: 2rem;">
                                                <x-danger-button type="button"
                                                    class="btn btn-danger remove-item">Remove</x-danger-button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <x-primary-button type="button" class="btn btn-secondary add-item mb-3">Add
                                                Another Item</x-primary-button>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="students">Students</label>
                                        <select name="students[]" id="students" class="form-control "
                                            multiple="multiple" style="width: 100%;">
                                        </select>
                                    </div>
                                    <x-primary-button type="submit" class="btn btn-primary">Submit</x-primary-button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('sctipts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#serial-0').select2({
                placeholder: "Select Serial",
                allowClear: true,
                width: '100%'
            });
            $('#dateNeeded').attr('min', new Date().toISOString().slice(0, 16));
            var itemIndex = 1;

            function addItemRow() {
                var items = @json(session('items', []));
                if (items.length === 0) {
                    console.error("No items found in the session.");
                    return;
                }

                // Collect all selected serials
                var selectedSerials = [];
                $('.serial-select').each(function() {
                    $(this).find('option:selected').each(function() {
                        selectedSerials.push($(this).val());
                    });
                });

                var itemRow = `
            <div class="item-row form-row mb-3">
                <div class="col col-2">
                    <label for="item-${itemIndex}">Equipment</label>
                    <select class="form-control validate item-select" id="item-${itemIndex}" name="items[${itemIndex}][item_id]">
                        <option value="">Select an item</option>`;

                $.each(items, function(index, item) {
                    itemRow += `<option value="${item.id}" data-brand="${item.brand}" data-quantity="${item.count}">
                        ${item.equipment} (${item.count} available)
                    </option>`;
                });

                itemRow += `</select>
                </div>
                <div class="col col-4">
                    <label for="serial-${itemIndex}">Items</label>
                    <select class="form-control validate serial-select" id="serial-${itemIndex}" name="items[${itemIndex}][items][]" multiple>
                        <option value="">Select Serial</option>
                    </select>
                </div>
                <div class="col">
                    <label for="qty-${itemIndex}">Quantity:</label>
                    <input readonly type="number" class="form-control validate quantity-input" id="qty-${itemIndex}" name="items[${itemIndex}][quantity]" data-max-quantity="0">
                </div>
                <div class="col">
                    <label for="brand-${itemIndex}">Brand:</label>
                    <input type="text" class="form-control validate" id="brand-${itemIndex}" name="items[${itemIndex}][brand]" readonly>
                </div>
                <div class="col">
                    <label for="remarks-${itemIndex}">Condition:</label>
                    <input type="text" class="form-control validate" id="remarks-${itemIndex}" name="items[${itemIndex}][remarks]" placeholder="Enter condition">
                </div>
                <input type="hidden" id="item-id-${itemIndex}" name="items[${itemIndex}][item_id]" value="">
                <div class="col-md-3 form-group align-self-end absolute" style="margin-top: 2rem;">
                    <x-danger-button type="button" class="btn btn-danger remove-item">Remove</x-danger-button>
                </div>
            </div>`;

                $('#items').append(itemRow);
                $('#serial-' + itemIndex).select2({
                    placeholder: "Select Serial",
                    allowClear: true,
                    width: '100%'
                });

                itemIndex++;
            }

            $(document).on('change', '.item-select', function() {
                var $this = $(this);
                var selectedOption = $this.find('option:selected');
                var itemRow = $this.closest('.item-row');
                var brandInput = itemRow.find('input[name$="[brand]"]');
                var qtyInput = itemRow.find('input[name$="[quantity]"]');
                var itemIdInput = itemRow.find('input[name$="[item_id]"]');
                var serialDropdown = itemRow.find('.serial-select');

                var itemId = selectedOption.val();
                var maxQuantity = selectedOption.data('quantity');
                var brand = selectedOption.data('brand');

                brandInput.val(brand);
                qtyInput.attr('data-max-quantity', maxQuantity);
                itemIdInput.val(itemId);

                $.ajax({
                    url: '{{ route('find-items') }}',
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                        request_id: itemId,
                        category: $('#category').val()
                    },
                    success: function(response) {
                        console.log(response);

                        // Collect already selected serials
                        var selectedSerials = [];
                        $('.serial-select').each(function() {
                            $(this).find('option:selected').each(function() {
                                selectedSerials.push($(this).val());
                            });
                        });

                        serialDropdown.empty().append(
                            '<option value="">Select Serial</option>');
                        $.each(response[0], function(index, serial) {
                            // Exclude serials that are already selected
                            if (!selectedSerials.includes(serial.id.toString())) {
                                serialDropdown.append(
                                    `<option value="${serial.id}">${serial.serial_no}</option>`
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('input', '.quantity-input', function() {
                var $this = $(this);
                var maxQuantity = parseInt($this.data('max-quantity'), 10);
                var value = parseInt($this.val(), 10);

                if (value > maxQuantity) {
                    $this.val(maxQuantity);
                    alert('Quantity cannot exceed available stock.');
                } else if (value < 1) {
                    $this.val(1);
                }
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
            });

            $(document).on('change', '.serial-select', function() {
                var $this = $(this);
                var selectedSerials = $this.val();
                var itemRow = $this.closest('.item-row');
                var qtyInput = itemRow.find('.quantity-input');

                qtyInput.val(selectedSerials.length);
            });

            $('.add-item').on('click', function() {
                addItemRow();
            });
        });



        $(document).ready(function() {
            $('#submitForm').submit(function(e) {
                let formIsValid = true;

                $('.validate').each(function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        formIsValid = false;
                        setTimeout(() => {
                            $(this).removeClass('is-invalid');
                        }, 1500);
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!formIsValid) {
                    event.preventDefault();
                }
            })

            $('#students').select2({
                tags: true,
                tokenSeparators: ['\n'],
                placeholder: 'Add student names and press enter after completing the name ',
                allowClear: true
            });

            function addStudent(name) {
                var newOption = new Option(name, name, false, true);
                $('#students').append(newOption).trigger('change');
            }
        })
    </script>
@endsection
