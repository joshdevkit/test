@extends('layouts.officeadmin')

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
                        <h1 class="m-0 text-success ">Add Item</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title">Edit Office Supplies</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Equipment -->
                            <form action="{{ route('supplies.update', $supply->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="exampleInputQuantity">{{ __('Name of the Equipment') }}</label>
                                    <input type="text" class="form-control" id="item" name="item"
                                        value="{{ old('item', $supply->item) }}" placeholder="{{ __('item') }}">
                                </div>
                                <!-- Brand -->
                                <div class="form-group">
                                    <label for="exampleInputQuantity">{{ __('Description/Specification') }}</label>
                                    <input type="text" class="form-control" id="brand_description"
                                        name="brand_description"
                                        value="{{ old('brand_description', $supply->brand_description) }}"
                                        placeholder="{{ __('brand_description ') }}">
                                </div>
                                <!-- Quantity -->
                                <label for="serial_no">{{ __('Serial No') }}</label>
                                <select class="form-control select2" id="serial_no" name="serial_no[]" multiple="multiple"
                                    style="width: 100%;">
                                    @foreach ($supply->items as $item)
                                        <option value="{{ $item->serial_no }}"
                                            {{ in_array($item->serial_no, old('serial_no', $supply->items->pluck('serial_no')->toArray())) ? 'selected' : '' }}>
                                            {{ $item->serial_no }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-6">
                            <!-- Condition -->
                            <div class="form-group">
                                <label for="exampleInputCondition">{{ __('Unit') }}</label>
                                <select class="form-control select2" id="unit" name="unit"
                                    value="{{ old('unit', $supply->unit) }}" data-placeholder="{{ __('unit') }}"
                                    style="width: 100%;">
                                    <option value="unit" {{ old('unit', $supply->unit) == 'unit' ? 'selected' : '' }}>
                                        unit</option>
                                    <option value="pcs" {{ old('unit', $supply->unit) == 'pcs' ? 'selected' : '' }}>pcs
                                    </option>
                                    <option value="set" {{ old('unit', $supply->unit) == 'set' ? 'selected' : '' }}>set
                                    </option>
                                    <option value="box" {{ old('unit', $supply->unit) == 'box' ? 'selected' : '' }}>box
                                    </option>
                                    <option value="-" {{ old('unit', $supply->unit) == '-' ? 'selected' : '' }}>-
                                    </option>
                                </select>
                            </div>

                            <!-- Date Acquired -->
                            <div class="form-group">
                                <label for="exampleInputQuantity">Location/Room</label>
                                <input type="text" class="form-control" id="location" name="location"
                                    value="{{ old('location', $supply->location) }}"
                                    data-placeholder="{{ __('location') }}">
                            </div>
                            <!-- Date Disposal -->
                            <div class="form-group">

                                <label for="date_delivered" name="date_delivered"
                                    class="form-label">{{ 'Date Delivered' }}</label>
                                <input type="date" class="form-control" id="date_delivered" name="date_delivered"
                                    value="{{ old('date_delivered', $supply->date_delivered) }}"
                                    data-placeholder="{{ __('date_delivered') }}">


                            </div>
                        </div>
                    </div>


                    <!-- Save button -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-right">
                            <button type="submit"
                                class="btn bg-green-500 text-white btn-success">{{ __('Update') }}</button>
                            <a type="cancel" class="btn btn-danger" href="{{ url('/supplies') }}">{{ __('Exit') }}</a>

                        </div>
                        </form>


                    </div>
                </div>


                <!-- /.card-body -->
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                tokenSeparators: ['\n'],
                placeholder: 'Add multiple serial no here and press enter ',
                allowClear: true
            });
        });
    </script>
@endsection
