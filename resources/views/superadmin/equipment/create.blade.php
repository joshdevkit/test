@extends('layouts.superadmin')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
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
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Add Equipment</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-success">
                    <div class="card-header">
                        <h1 class="card-title"> Office Equipment</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Equipment -->
                                <form action="{{ route('admin.equipment.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="serial_no">Serial No</label>
                                        <select name="serial_no[]" id="serial_no" class="form-control"
                                            multiple="multiple" style="width: 100%;">
                                        </select>
                                    </div>
                                    <!-- Brand -->
                                    <div class="form-group">
                                        <label for="exampleInputQuantity">Description/Specification</label>
                                        <input type="text" class="form-control" id="brand_description"
                                            name="brand_description" placeholder="Enter Description/Specification">
                                    </div>
                                    <!-- Quantity -->
                                    <div class="form-group">
                                        <label for="exampleInputQuantity">Name of Equipment</label>
                                        <input type="text" class="form-control" id="item" name="item"
                                            placeholder="Enter Equipment">
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Condition -->
                                <div class="form-group">
                                    <label for="exampleInputCondition">{{ __('Unit') }}</label>
                                    <select class="form-control select2" id="unit" name="unit"
                                        data-placeholder="Select Unit" style="width: 100%;">
                                        <option>pcs</option>
                                        <option>set</option>
                                        <option>box</option>
                                        <option>-</option>
                                    </select>
                                </div>

                                <!-- Date Acquired -->
                                <div class="form-group">
                                    <label for="exampleInputQuantity">Location/Room</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        placeholder="Enter Location">
                                </div>
                                <!-- Date Disposal -->
                                <div class="form-group">

                                    <label for="date_delivered" name="date_delivered" class="form-label">{{ 'Date
                                        Delivered' }}</label>
                                    <input type="date" class="form-control" id="date_delivered"
                                        placeholder="{{ __('date_delivered') }}" name="date_delivered"
                                        value="{{ old('date_delivered') }}">


                                </div>
                                <!-- Save button -->
                                <div class="row mt-3">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-success">{{ __('Save') }}</button>
                                        <a type="cancel" class="btn btn-danger"
                                            href="{{ url('superadmin/equipment') }}">{{ __('Exit') }}</a>
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
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
            $('#serial_no').select2({
                tags: true,
                tokenSeparators: ['\n'],
                placeholder: 'Add multiple serial no here and press enter ',
                allowClear: true
            });

        })
</script>
@endsection