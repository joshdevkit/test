@extends('layouts.superadmin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
                    <h1 class="text-success">General Construction's List of Equipment</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('superadmin.construction.create') }}" class="btn btn-sm btn-success"><i
                                    class="fas fa-plus"></i> Add
                                new
                                item</a>
                            <div class="flex float-right">
                                <button class="btn btn-primary btn-sm ml-2" id="print-btn">
                                    <i class="fas fa-print"></i> Print
                                </button>

                                <button class="btn btn-primary btn-sm ml-2" id="print-all-btn">
                                    <i class="fas fa-print"></i> Print All
                                </button>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Equipment</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Date Acquired</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($constructions as $construction)
                                    <tr data-entry-id="{{ $construction->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $construction->equipment }}</td>
                                        <td>{{ $construction->brand }}</td>
                                        <td>{{ $construction->quantity }}</td>
                                        <td>{{ $construction->unit }}</td>
                                        <td>{{ $construction->date_acquired }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                <a href="{{ route('superadmin.construction.show', ['id' => $construction->id]) }}"
                                                    class="btn btn-info">View More</a>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.construction.edit', ['id' => $construction->id]) }}">
                                                    <button class="btn btn-secondary">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </button>
                                                </form>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.construction.destroy', ['id' => $construction->id]) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
</div>
@endsection

@section('scripts')

@endsection