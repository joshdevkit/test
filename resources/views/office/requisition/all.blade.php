@extends('layouts.officeadmin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
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
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Recent Requisition</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>SOURCE OF FUND</th>
                                    <th> PURPOSE / PROJECT</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>{{ $request->source_of_fund }}</td>
                                        <td>{{ $request->purpose_project }}</td>
                                        <td>{{ $request->status }}</td>
                                        <td>
                                            @if ($request->status != 'Pending')
                                                <a href="{{ route('print-record', ['id' => $request->id]) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                            @else
                                                <span class="badge badge-sm bg-warning"> Waiting for approval</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
