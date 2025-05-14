@extends('layouts.dean')

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
                        <h1 class = "text-success">Office Equipment Items History</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example1" class="table table-borderd table-striped">
                                    <thead>
                                        <tr>
                                            <th>EQUIPMENT SERIAL</th>
                                            <th>BORROWED BY</th>
                                            <th>DATE BORROWED</th>
                                            <th>DATE RETURNED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($itemHistory as $item)
                                            <tr>
                                                <td>{{ $item->items->serial_no }}</td>
                                                <td>{{ $item->requestFrom->requestBy->name }}</td>
                                                <td>{{ date('F d, Y h:i A', strtotime($item->created_at)) }}</td>
                                                <td>{{ date('F d, Y h:i A', strtotime($item->date_returned)) }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div>
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
