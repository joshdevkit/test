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
                        <h1 class="m-0 text-success ">Show Computer Engineering Equipment</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4>Show {{ $data->equipment }} Details</h4>
                </div>
                <div class="card-body">
                    <div class="card-title">
                        <h4>Brand: {{ $data->brand }}</h4>
                        <h4>Unit: {{ $data->unit }}</h4>
                        <h4>Date Acquired: {{ $data->date_acquired }}</h4>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Serial Number</th>
                                <th>Condition</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->items as $item)
                                <tr>
                                    <td>{{ $item->serial_no }}</td>
                                    <td>{{ $item->condition }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a type="cancel" class="btn btn-danger float-right"
                        href="{{ url('/superadmin/computer_engineering/') }}">{{ __('Exit') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
