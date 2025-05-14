@extends('layouts.officeadmin')

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
                        <h1 class="m-0 text-success ">Supplies Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">

                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">

            <div class="card card-success">
                <div class="card-header">
                    <h1 class="card-title">
                        Office Supplies

                    </h1>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <strong>SERIAL NO:</strong><br>
                            @foreach ($data['items'] as $item)
                                {{ $item->serial_no }}@if (!$loop->last)
                                    <br>
                                @endif
                            @endforeach
                        </div>
                        <div class="col">
                            <strong>ID:</strong> {{ $data->id }}<br>
                            <strong>QUANTITY:</strong> {{ $data->items->count() }}<br>
                            <strong>UNIT:</strong> {{ $data->unit }}<br>
                            <strong>ITEMS:</strong> {{ $data->item }}<br>
                            <strong>BRAND DESCRIPTION:</strong> {{ $data->brand_description }}<br>
                            <strong>LOCATION:</strong> {{ $data->location }}<br>
                            <strong>DATE DELIVERED:</strong> {{ $data->date_delivered }}<br>
                        </div>
                    </div>
                    <a class="btn btn-danger float-right" href="{{ url('/office/supplies') }}">Exit</a>
                </div>
            </div>
        </div>
    </div>

@endsection
