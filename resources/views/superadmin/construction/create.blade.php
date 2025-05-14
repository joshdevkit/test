@extends('layouts.superadmin')

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
                        <h1 class="m-0 text-success ">Add Testing and Contruction Equipment</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">

            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title"> Testing and Contruction</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('superadmin.construction.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1">Equipment</label>
                                <input type="text" class="form-control" id="equipment" name="equipment"
                                    placeholder="Enter Equipment">
                            </div>
                            <!-- Brand -->
                            <div class="col-md-6">
                                <label for="exampleInputPassword1">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand"
                                    placeholder="Enter Brand">
                            </div>
                            <div class="col-md-6">
                                <label for="date_acquired" name="date_acquired"
                                    class="form-label">{{ 'Date Acquired' }}</label>
                                <input type="date" class="form-control" id="date_acquired"
                                    placeholder="{{ __('date_acquired') }}" name="date_acquired"
                                    value="{{ old('date_acquired') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputCondition">{{ __('Unit') }}</label>
                                <select class="form-control select2" id="unit" name="unit"
                                    data-placeholder="Select Unit" style="width: 100%;">
                                    <option>unit</option>
                                    <option>pcs</option>
                                    <option>set</option>
                                    <option>box</option>
                                    <option>-</option>


                                </select>
                            </div>
                        </div>
                        <div class="row" id="form-template">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputCondition">{{ __('Serial Number') }}</label>
                                    <input type="text" name="serial_no[]" id="serial_no[]"
                                        class="form-control serial-value">
                                </div>
                                <div id="message-0" class="message"></div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="exampleInputCondition">{{ __('condition') }}</label>
                                    <select class="form-control" id="condition[]" name="condition[]"
                                        data-placeholder="Select Condition" style="width: 100%;">
                                        <option>Good</option>
                                        <option>For Repair</option>
                                        <option>For Upgrading</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1" style="margin-top: 2rem">
                                <div class="form-group">
                                    <button type="button" id="add-row" class="btn btn-primary bg-blue-500 text-white"><i
                                            class="fas fa-plus"></i></button>
                                </div>
                            </div>

                        </div>
                        <div id="dynamic_serial">

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Save') }}</button>
                                <a type="cancel" class="btn btn-danger"
                                    href="{{ route('constructions.index') }}">{{ __('Exit') }}</a>
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
            let rowCount = 0;
            $('#add-row').click(function() {
                var newRow = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputCondition">{{ __('Serial Number') }}</label>
                            <input type="text" name="serial_no[]" class="form-control serial-value">
                        </div>
                        <div id="message-${rowCount}" class="message"></div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="exampleInputCondition">{{ __('Condition') }}</label>
                            <select class="form-control" name="condition[]" data-placeholder="Select Condition" style="width: 100%;">
                                <option>Good</option>
                                <option>For Repair</option>
                                <option>For Upgrading</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1" style="margin-top: 2rem">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger bg-blue-500 text-white remove-row"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                </div>`;
                $('#dynamic_serial').append(newRow);
            });

            // Remove row functionality
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.row').remove();
            });

            $(document).on('input', '.serial-value', function() {
                var targetInput = $(this);
                var val = targetInput.val();
                var messageElement = targetInput.closest('.row').find(
                    '.message');

                $.ajax({
                    url: "{{ route('check-serial') }}",
                    type: 'GET',
                    data: {
                        _token: "{{ csrf_token() }}",
                        serial: val,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.exist && val.length > 0) {
                            targetInput.addClass('is-invalid');
                            messageElement.html(
                                "<p class='text-danger'>Serial already exists</p>");
                            $('#submitBtn').prop('disabled', true);
                        } else {
                            targetInput.removeClass('is-invalid').addClass('is-valid');
                            messageElement.html("");
                            $('#submitBtn').prop('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
@endsection
