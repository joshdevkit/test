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
                        <h1 class = "text-success">Office Equipment Items</h1>
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
                                            <th>ID</th>
                                            <th>EQUIPMENT</th>
                                            <th>EQUIPMENT SERIAL</th>
                                            <th>LOCATION/ROOM</th>
                                            <th>DATE DELIVERED</th>
                                            <th>STATUS</th>
                                            <TH>NOTE</TH>
                                            <th>BORROW HISTORY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equipmentItems as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->equipment->item }}</td>
                                                <td>{{ $item->serial_no }}</td>
                                                <td>{{ $item->equipment->location }}</td>
                                                <td>{{ date('F d, Y ', strtotime($item->equipment->date_delivered)) }}
                                                </td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->note ?? '' }}</td>
                                                <td>
                                                    <a href="{{ route('site.equipment-items-history.index', ['id' => $item->id]) }}"
                                                        class="btn btn-info btn-sm"> <i class="fas fa-list"></i> History</a>
                                                </td>
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
