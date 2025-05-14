@extends('layouts.userapp')
@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-success">Request</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <x-app-layout>
                    <div class="card card-success">
                        <div class="card-header">
                            <h2 class="card-title">Office Requisition</h2>
                        </div>
                        <div class="card-body">
                            <form id="requisition-form" method="POST" action="{{ route('office_user.selectCategory') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category"
                                        onchange="this.form.submit()" required>
                                        <option value="">Select a category</option>
                                        <option value="equipments"
                                            {{ session('category') == 'equipments' ? 'selected' : '' }}>Equipment
                                        </option>
                                        <option value="supplies" {{ session('category') == 'supplies' ? 'selected' : '' }}>
                                            Supplies
                                        </option>
                                    </select>
                                </div>
                            </form>

                            @if (session('category'))
                                <form action="{{ route('office_user.store') }}" method="POST">
                                    @csrf
                                    <div class="form-row mb-3">
                                        <div class="col-md-6">
                                            <label for="user_name">Name</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <input type="hidden" name="category" value="{{ ucfirst(session('category')) }}">
                                    <div id="items">
                                        <div class="item-row">
                                            <div class="form-row">
                                                <div
                                                    class="col-md-{{ session('category') == 'equipments' ? '3' : '6' }} form-group">
                                                    <label for="item">Item</label>
                                                    <select id="selectedInitial"
                                                        class="form-control selected-equipment item-select"
                                                        name="items[0][item_id]" required>
                                                        <option value="">Select an item</option>
                                                        @if (session('items'))
                                                            @foreach (session('items') as $item)
                                                                <option value="{{ $item['id'] }}"
                                                                    data-quantity="{{ $item['count'] }}">
                                                                    {{ $item['item'] }} ({{ $item['count'] }} available)
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option value="">No items available</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                @if (session('category') == 'equipments')
                                                    <div class="col-md-6 form-group">
                                                        <label for="item">Equipment Items Serial</label>
                                                        <select class="form-control serial-select" name="serial-0[]"
                                                            id="serial-0" multiple>
                                                            <option value="">Items</option>
                                                        </select>
                                                    </div>
                                                @endif
                                                @if (session('category') == 'supplies')
                                                    <div class="col-md-3 form-group">
                                                        <label for="quantity">Quantity</label>
                                                        <input type="number" class="form-control quantity-input"
                                                            id="qty-0" name="items[0][quantity]" required
                                                            data-max-quantity="0" min="0">
                                                        <input type="hidden" name="items[0][actual_quantity]"
                                                            id="hidden-qty-0" value="0">
                                                    </div>
                                                @endif
                                                <div class="col-md-3 form-group align-self-end">
                                                    <x-danger-button type="button"
                                                        class="btn btn-danger remove-item">Remove</x-danger-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <x-primary-button type="button" class="btn btn-secondary add-item mb-3">Add
                                                Another Item</x-primary-button>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="purpose">Purpose</label>
                                        <textarea class="form-control" id="purpose" name="purpose" rows="4" required></textarea>
                                    </div>
                                    <x-primary-button type="submit" class="btn btn-primary">Submit</x-primary-button>
                                </form>
                            @endif

                        </div>
                    </div>
                </x-app-layout>
            </div>
        </div>
    </div>



@endsection
@section('sctipts')
    <script>
        $(document).ready(function() {
            $('#serial-0').select2({
                placeholder: "Select Serial",
                allowClear: true,
                width: '100%'
            });

            let itemIndex = 1;
            let selectedSerials = {}; // Tracks serials for each item
            let selectedItemsWithItsSerials = [];
            let selectedEquipmentId;

            // Equipment selection change (fetch serials)
            $(document).on('change', '.selected-equipment', function() {
                var $this = $(this);
                var itemActualQuantity = $this.find('option:selected').data('quantity');

                var selectedOption = $this.find('option:selected');
                var itemId = selectedOption.val();
                var quantityInput = $this.closest('.item-row').find('.quantity-input');
                quantityInput.data('max-quantity', itemActualQuantity);
                quantityInput.attr('data-max-quantity', itemActualQuantity);
                selectedEquipmentId = itemId;
                console.log("Selected Item: ", selectedEquipmentId, "Available Quantity: ",
                    itemActualQuantity);

                // Initialize selectedSerials for the item if not already initialized
                if (!selectedSerials[itemId]) {
                    selectedSerials[itemId] = [];
                }

                // Check if this equipment already has all serials selected
                let existingItem = selectedItemsWithItsSerials.find(item => item.equipment_id === itemId);


                $.ajax({
                    url: '{{ route('office_user.items-selected', ['']) }}/' + itemId,
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        console.log("Equipment Serials: ", response);
                        console.log("Updated Serials to be compared if matching equipment: ",
                            selectedItemsWithItsSerials);
                        if (existingItem && existingItem.serials.length === response.length) {
                            return;
                        }
                        var serialSelect = $this.closest('.item-row').find('.serial-select');
                        serialSelect.empty();
                        serialSelect.append('<option value="">Select Serial</option>');

                        // Filter available serials, excluding the ones already selected globally
                        let availableSerials = response.filter(item => {
                            // Check if the current serial is already selected
                            return !Object.values(selectedSerials).flat().includes(item
                                .id.toString());
                        });

                        console.log("Available Serials: ", availableSerials);

                        // Additional filter to exclude serials that are already selected for the equipment
                        availableSerials = availableSerials.filter(item => {
                            // Check if this serial is already in the selectedItemsWithItsSerials array
                            let equipmentMatch = selectedItemsWithItsSerials.find(
                                itemInList => itemInList.equipment_id ==
                                selectedEquipmentId);
                            if (equipmentMatch) {
                                // Check if the serial is in the existing serials for this equipment
                                return !equipmentMatch.serials.includes(item.id);
                            }
                            return true; // No match, include this serial
                        });

                        availableSerials.forEach(function(item) {
                            serialSelect.append(
                                `<option value="${item.id}">${item.serial_no}</option>`
                            );
                        });

                        serialSelect.select2({
                            placeholder: "Select Serial",
                            allowClear: true,
                            width: '100%'
                        });
                    }
                });
            });

            // Serial selection change
            $(document).on('change', '.serial-select', function() {
                var $this = $(this);
                var selectedSerialsArray = $this.val() || []; // Selected serials (may be empty)
                var itemId = $this.closest('.form-row').find('.selected-equipment').val();

                // Initialize selectedSerials for the item if not already initialized
                if (!selectedSerials[itemId]) {
                    selectedSerials[itemId] = [];
                }

                // Update selectedSerials for the item
                selectedSerials[itemId] = selectedSerialsArray;
                console.log(selectedSerials); // Debug: Show the selected serials object

                // Check if the equipment_id already exists in selectedItemsWithItsSerials
                let existingItem = selectedItemsWithItsSerials.find(item => item.equipment_id === itemId);

                if (existingItem) {
                    // If the equipment_id exists, append the new serials to the existing array
                    existingItem.serials = [...new Set([...existingItem.serials, ...selectedSerialsArray])];
                } else {
                    // If the equipment_id doesn't exist, create a new entry
                    selectedItemsWithItsSerials.push({
                        equipment_id: itemId,
                        serials: selectedSerialsArray
                    });
                }

                console.log("Updated Selected Items Serials: ", selectedItemsWithItsSerials);
                console.log("Selected Equipment ID: ", selectedEquipmentId);
            });

            $(document).on('input', '.quantity-input', function() {
                var $this = $(this);
                var enteredQuantity = parseInt($this.val());
                var maxQuantity = $this.data('max-quantity');
                var totalEnteredQuantity = 0;

                // Calculate total entered quantity for the same item id
                $('.quantity-input').each(function() {
                    var itemId = $(this).closest('.item-row').find('.selected-equipment').val();
                    if (itemId == selectedEquipmentId) {
                        totalEnteredQuantity += parseInt($(this).val()) || 0;
                    }
                });

                // Check if entered quantity exceeds available quantity
                if (totalEnteredQuantity > maxQuantity) {
                    alert('Entered quantity exceeds available quantity');
                    $this.val(maxQuantity - (totalEnteredQuantity -
                        enteredQuantity)); // Adjust quantity input
                }
            });

            function updateMaxQuantity(selectElement) {
                const selectedOption = $(selectElement).find('option:selected');
                const maxQuantity = selectedOption.data('quantity');

                if (maxQuantity !== undefined) {
                    const itemIndex = $(selectElement).closest('.item-row').find('input.quantity-input').attr('id')
                        .split('-')[1];

                    $(`#qty-${itemIndex}`).attr('data-max-quantity', maxQuantity);
                    $(`#hidden-qty-${itemIndex}`).val(maxQuantity);
                }
            }

            function handleSelectChange(selectElement) {
                selectElement.on('change', function() {
                    updateMaxQuantity($(this));
                });
            }

            function handleQuantityValidation() {
                $(document).on('input', '.quantity-input', function() {
                    const $this = $(this);
                    const maxQuantity = parseInt($this.attr('data-max-quantity'), 10);
                    const enteredQuantity = parseInt($this.val(), 10);

                    if (enteredQuantity > maxQuantity) {
                        alert('Quantity cannot exceed available stock.');
                        $this.val(maxQuantity);
                    }
                });
            }


            const initialSelect = $('#items select[name="items[0][item_id]"]');

            handleSelectChange(initialSelect);

            $('.add-item').on('click', function() {
                const itemRow = `
                <div class="item-row">
                    <div class="form-row">
                        <div class="col-md-{{ session('category') == 'equipments' ? '3' : '6' }} form-group">
                            <label for="item">Item</label>
                            <select class="form-control item-select selected-equipment" name="items[${itemIndex}][item_id]" required>
                                <option value="">Select an item</option>
                                @if (session('items') && count(session('items')) > 0)
                                    @foreach (session('items') as $item)
                                        <option value="{{ $item['id'] }}" data-quantity="{{ $item['count'] }}">
                                            {{ $item['item'] }} ({{ $item['count'] }} available)
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">No items available</option>
                                @endif
                            </select>
                        </div>
                        @if (session('category') == 'equipments')
                            <div class="col-md-6 form-group">
                                <label for="serial-${itemIndex}">Equipment Items Serial</label>
                                <select class="form-control serial-select" name="serial-${itemIndex}[]" id="serial-${itemIndex}" multiple>
                                    <option value="">Select Serial</option>
                                </select>
                            </div>
                        @endif
                        @if (session('category') == 'supplies')
                            <div class="col-md-3 form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control quantity-input" id="qty-${itemIndex}" name="items[${itemIndex}][quantity]" required data-max-quantity="0" min="0">
                                <input type="hidden" name="items[${itemIndex}][actual_quantity]" id="hidden-qty-${itemIndex}" value="0">
                            </div>
                        @endif
                        <div class="col-md-3 form-group align-self-end">
                            <x-danger-button type="button"
                            class="btn btn-danger remove-item">Remove</x-danger-button>
                        </div>
                    </div>
                </div>`;

                $('#items').append(itemRow);

                // Initialize select2 for new serial select
                $('#serial-' + itemIndex).select2({
                    placeholder: "Select Serial",
                    allowClear: true,
                    width: '100%'
                });

                itemIndex++;
            });

            $(document).on('click', '.remove-item', function() {
                var $this = $(this);
                var serialSelect = $this.closest('.item-row').find('.serial-select');
                var itemId = $this.closest('.form-row').find('.selected-equipment').val();
                var selectedSerialsArray = serialSelect.val();
                console.log("Selected Serial Arrays: ", selectedSerialsArray);

                // Ensure that the serials are correctly removed from the selectedSerials object
                if (itemId && selectedSerials[itemId]) {
                    // Filter out the serials that are selected in the current row
                    selectedSerials[itemId] = selectedSerials[itemId].filter(serial => !selectedSerialsArray
                        .includes(serial));
                }

                // Ensure that the serials are correctly removed from the selectedItemsWithItsSerials object
                let existingItemIndex = selectedItemsWithItsSerials.findIndex(item => item.equipment_id ===
                    itemId);
                if (existingItemIndex !== -1) {
                    selectedItemsWithItsSerials[existingItemIndex].serials = selectedItemsWithItsSerials[
                        existingItemIndex].serials.filter(serial => !selectedSerialsArray.includes(
                        serial));

                    // If there are no serials left for this equipment, remove the entry
                    if (selectedItemsWithItsSerials[existingItemIndex].serials.length === 0) {
                        selectedItemsWithItsSerials.splice(existingItemIndex, 1);
                    }
                }

                $this.closest('.item-row').remove();

                // Update available serials for all items
                updateAvailableSerials();
            });

            function updateAvailableSerials() {
                $('.selected-equipment').each(function() {
                    var itemId = $(this).val();

                    if (itemId) {
                        $.ajax({
                            url: '{{ route('office_user.items-selected', ['']) }}/' + itemId,
                            type: 'GET',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                var serialSelect = $(this).closest('.item-row').find(
                                    '.serial-select');
                                serialSelect.empty();
                                serialSelect.append('<option value="">Select Serial</option>');

                                let availableSerials = response.filter(item => {
                                    return !Object.values(selectedSerials).flat()
                                        .includes(item.id);
                                });

                                availableSerials.forEach(function(item) {
                                    serialSelect.append(
                                        `<option value="${item.id}">${item.serial_no}</option>`
                                    );
                                });

                                serialSelect.select2({
                                    placeholder: "Select Serial",
                                    allowClear: true,
                                    width: '100%'
                                });
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection
