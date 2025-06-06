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
                    <h1 class="text-success">Computer Engineering's List of Equipment</h1>
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
                            <a href="{{ route('superadmin.computer_engineering.create') }}"
                                class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add new
                                items</a>
                            <button class="btn btn-primary btn-sm float-right" id="print-all-btn">
                                <i class="fas fa-print"></i> Print All
                            </button>
                            <button class="btn btn-primary btn-sm float-right mr-2" id="print-btn">
                                <i class="fas fa-print"></i> Print
                            </button>

                            <!-- /.card-header -->

                            <!-- /.card-body -->
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
                                    @foreach ($computerEngineering as $computer)
                                    <tr data-entry-id="{{ $computer->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $computer->equipment }}</td>
                                        <td>{{ $computer->brand }}</td>
                                        <td>{{ $computer->quantity }}</td>
                                        <td>{{ $computer->unit }}</td>
                                        <td>{{ $computer->date_acquired }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                <a href="{{ route('superadmin.computer_engineering.show', ['id' => $computer->id]) }}"
                                                    class="btn btn-info">View More</a>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.computer_engineering.edit', ['id' => $computer->id]) }}">
                                                    <button class="btn btn-secondary">
                                                        <i class="fa fa-pencil-alt"></i>
                                                    </button>
                                                </form>
                                                &nbsp
                                                <form
                                                    action="{{ route('superadmin.computer_engineering.destroy', ['id' => $computer->id]) }}"
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
<script>
    document.getElementById("print-btn").addEventListener("click", function () {
        let rows = [];
        document.querySelectorAll("#example1 tbody tr").forEach((row) => {
            let rowData = [];
            row.querySelectorAll("td").forEach((td) => {
                rowData.push(td.innerText.trim());
            });
            rows.push(rowData);
        });

        fetch("{{ route('print.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ data: rows, path: "computer_engineering", title: 'GENERAL CONSTRUCTION' }),
        })
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            window.open(url, "_blank");
        })
        .catch(error => console.error("Error:", error));
    });

    document.getElementById("print-all-btn").addEventListener("click", function () {
        fetch("{{ route('print-all-items') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ path: "computer_engineering", title: 'GENERAL CONSTRUCTION', category: 1 }),
        })
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            window.open(url, "_blank");
        })
        .catch(error => console.error("Error:", error));
    });
</script>

@endsection