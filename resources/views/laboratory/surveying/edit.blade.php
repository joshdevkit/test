@extends('layouts.labadmin')

@section('content')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">

                <!-- Page Heading -->


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-success ">Edit Surveying Equipment</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-success">
                        <div class="card-header">
                            <h1 class="card-title">Surveying</h1>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('surveyings.update', $surveying->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{ __('Equipment') }}</label>
                                            <input type="text" class="form-control" id="equipment" name="equipment"
                                                value="{{ old('equipment', $surveying->equipment) }}"
                                                placeholder="{{ __('equipment') }}">
                                        </div>
                                        <!-- Brand -->
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">{{ __('Brand') }}</label>
                                            <input type="text" class="form-control" id="brand" name="brand"
                                                value="{{ old('brand', $surveying->brand) }}"
                                                placeholder="{{ __('brand') }}">
                                        </div>
                                        <!-- Quantity -->
                                        <div class="form-group">
                                            <label for="exampleInputCondition">{{ __('Unit') }}</label>
                                            <select class="form-control select2" id="unit" name="unit"
                                                value="{{ old('unit', $surveying->unit) }}"
                                                data-placeholder="{{ __('unit') }}" style="width: 100%;">
                                                <option value="unit"
                                                    {{ old('unit', $surveying->unit) == 'unit' ? 'selected' : '' }}>unit
                                                </option>
                                                <option value="pcs"
                                                    {{ old('unit', $surveying->unit) == 'pcs' ? 'selected' : '' }}>pcs
                                                </option>
                                                <option value="set"
                                                    {{ old('unit', $surveying->unit) == 'set' ? 'selected' : '' }}>set
                                                </option>
                                                <option value="box"
                                                    {{ old('unit', $surveying->unit) == 'box' ? 'selected' : '' }}>box
                                                </option>
                                                <option value="-"
                                                    {{ old('unit', $surveying->unit) == '-' ? 'selected' : '' }}>-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Condition -->
                                        <div class="form-group">
                                            <label for="exampleInputCondition">{{ __('Condition') }}</label>
                                            <select class="form-control select2" id="condition"
                                                name="condition"value="{{ old('condition', $surveying->condition) }}"
                                                data-placeholder="{{ __('condition') }}"style="width: 100%;">
                                                <option>Good</option>
                                                <option>For Repair</option>
                                                <option>For Upgrading</option>

                                            </select>
                                        </div>
                                        <!-- Date Acquired -->
                                        <div class="form-group">

                                            <label for="date_acquired" name="date_acquired"
                                                class="form-label">{{ 'Date Acquired' }}</label>
                                            <input type="date" class="form-control" id="date_acquired"
                                                name="date_acquired"
                                                value="{{ old('date_acquired', $surveying->date_acquired) }}" />

                                        </div>
                                        <!-- Date Disposal -->

                                    </div>

                                    <div class="col-md-12">
                                        <h3><strong>Items</strong></h3>
                                        <div id="items-container" class="row mt-2">
                                            @foreach ($surveying->items as $item)
                                                <div class="col-md-4 mb-3 item-card" data-id="{{ $item->id }}">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <label for="serial_no_{{ $item->id }}">Serial
                                                                    No</label>
                                                                <input type="hidden" name="serial_id[]"
                                                                    value="{{ $item->id }}">
                                                                <input type="text" class="form-control"
                                                                    id="serial_no_{{ $item->id }}"
                                                                    name="items[{{ $item->id }}][serial_no]"
                                                                    value="{{ old('items.' . $item->id . '.serial_no', $item->serial_no) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label
                                                                    for="condition_{{ $item->id }}">Condition</label>
                                                                <select class="form-control"
                                                                    id="condition_{{ $item->id }}"
                                                                    name="items[{{ $item->id }}][condition]"
                                                                    style="width: 100%;">
                                                                    <option value="Good"
                                                                        {{ old('items.' . $item->id . '.condition', $item->condition) == 'Good' ? 'selected' : '' }}>
                                                                        Good
                                                                    </option>
                                                                    <option value="For Repair"
                                                                        {{ old('items.' . $item->id . '.condition', $item->condition) == 'For Repair' ? 'selected' : '' }}>
                                                                        For Repair
                                                                    </option>
                                                                    <option value="For Upgrading"
                                                                        {{ old('items.' . $item->id . '.condition', $item->condition) == 'For Upgrading' ? 'selected' : '' }}>
                                                                        For Upgrading
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm remove-item mt-2">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" id="add-more"
                                            class="btn btn-primary bg-blue-500 text-white mt-3">Add More</button>
                                    </div>
                                    <!-- drscriptionm -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">{{ __('Description') }}</label>
                                            <textarea class="form-control" id="description" name="description" placeholder="{{ __('Enter description') }}"
                                                style="width: 100%;">{{ old('description', $surveying->description) }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <!-- Save button -->
                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit"
                                            class="btn bg-green-500 text-white btn-success">{{ __('Update') }}</button>
                                        <a type="cancel" class="btn btn-danger"
                                            href="{{ url('/surveyings') }}">{{ __('Exit') }}</a>

                                    </div>
                            </form>
                        </div>
                    </div>


                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let itemIndex = {{ $surveying->items->count() }};

            // Add more items
            $('#add-more').click(function() {
                itemIndex++;
                let newItem = `
        <div class="col-md-4 mb-3 item-card" data-id="new_${itemIndex}">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="serial_no_${itemIndex}">Serial No</label>
                        <input type="text" class="form-control" id="serial_no_${itemIndex}"
                            name="items[new_${itemIndex}][serial_no]" value="">
                    </div>
                    <div class="form-group">
                        <label for="condition_${itemIndex}">Condition</label>
                        <select class="form-control" id="condition_${itemIndex}"
                            name="items[new_${itemIndex}][condition]" style="width: 100%;">
                            <option value="Good">Good</option>
                            <option value="For Repair">For Repair</option>
                            <option value="For Upgrading">For Upgrading</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-item mt-2">Remove</button>
                </div>
            </div>
        </div>
        `;
                $('#items-container').append(newItem);
            });

            // Remove an item
            $('#items-container').on('click', '.remove-item', function() {
                $(this).closest('.item-card').remove();
            });
        });
    </script>
@endsection
