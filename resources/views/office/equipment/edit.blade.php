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
                        <h1 class="m-0 text-success ">Edit Equipment Item</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">

            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title">Edit Office Equipment</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="serial_no">{{ __('Serial No') }}</label>
                                    <select class="form-control select2" id="serial_no" name="serial_no[]"
                                        multiple="multiple" style="width: 100%;">
                                        @foreach ($equipment->items as $item)
                                            <option value="{{ $item->serial_no }}"
                                                {{ in_array($item->serial_no, old('serial_no', $equipment->items->pluck('serial_no')->toArray())) ? 'selected' : '' }}>
                                                {{ $item->serial_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="item">{{ __('Name of the Equipment') }}</label>
                                    <input type="text" class="form-control" id="item" name="item"
                                        value="{{ old('item', $equipment->item) }}" placeholder="{{ __('item') }}">
                                </div>
                                <div class="form-group">
                                    <label for="brand_description">{{ __('Description/Specification') }}</label>
                                    <input type="text" class="form-control" id="brand_description"
                                        name="brand_description"
                                        value="{{ old('brand_description', $equipment->brand_description) }}"
                                        placeholder="{{ __('brand_description') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit">{{ __('Unit') }}</label>
                                    <select class="form-control select2" id="unit" name="unit"
                                        data-placeholder="{{ __('unit') }}" style="width: 100%;">
                                        <option value="unit"
                                            {{ old('unit', $equipment->unit) == 'unit' ? 'selected' : '' }}>unit</option>
                                        <option value="pcs"
                                            {{ old('unit', $equipment->unit) == 'pcs' ? 'selected' : '' }}>pcs</option>
                                        <option value="set"
                                            {{ old('unit', $equipment->unit) == 'set' ? 'selected' : '' }}>set</option>
                                        <option value="box"
                                            {{ old('unit', $equipment->unit) == 'box' ? 'selected' : '' }}>box</option>
                                        <option value="-"
                                            {{ old('unit', $equipment->unit) == '-' ? 'selected' : '' }}>-</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="location">{{ __('Location/Room') }}</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="{{ old('location', $equipment->location) }}"
                                        data-placeholder="{{ __('location') }}">
                                </div>
                                <div class="form-group">
                                    <label for="date_delivered">{{ 'Date Delivered' }}</label>
                                    <input type="date" class="form-control" id="date_delivered" name="date_delivered"
                                        value="{{ old('date_delivered', $equipment->date_delivered) }}"
                                        data-placeholder="{{ __('date_delivered') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                <button type="submit"
                                    class="btn btn-success bg-green-500 text-white">{{ __('Update') }}</button>
                                    <a type="cancel" class="btn btn-danger"
                                    href="{{ url('/equipment') }}">{{ __('Exit') }}</a>
                            </div>
                        </div>
                    </form>
                </div>




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
