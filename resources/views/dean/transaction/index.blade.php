@extends('layouts.dean')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Transactions</h1>
                    </div>

                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">



                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">


                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Name</th>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Purpose</th>
                                            <th>Datetime Borrowed</th>
                                            <th>Status</th>
                                            <th>Days Not Returned</th>
                                            <th>Datetime Returned</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requisitions as $requisition)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $requisition->instructor->name }}</td>
                                                <td>{{ $requisition->category->name }}</td>
                                                <td>
                                                    {{ $requisition->items->flatMap->serials->where('borrow_status', 'Approved')->count() }}
                                                </td>

                                                <td>
                                                    {{ $requisition->activity }}
                                                </td>
                                                <td>{{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                                                </td>
                                                <td>{{ $requisition->status }}
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="d-flex flex-auto">
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('dean.transactions.show', ['id' => $requisition->id]) }}"><i
                                                            class="fas fa-eye"></i></a>
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
