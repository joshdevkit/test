@extends('layouts.dean')

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
                    <h1 class="text-success">Testing and Mechanics' List of Equipment</h1>
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
                                        <th>Description</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Date Acquired</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testings as $testing)
                                    <tr data-entry-id="{{ $testing->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $testing->equipment }}</td>
                                        <td>{{ $testing->description }}</td>
                                        <td>{{ $testing->brand }}</td>
                                        <td>{{ $testing->items->count() }}</td>
                                        <td>{{ $testing->unit }}</td>
                                        <td>{{ $testing->date_acquired }}</td>
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
        document.querySelectorAll("#adminLteDataTable tbody tr").forEach((row) => {
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
            body: JSON.stringify({ data: rows, title:  'TESTING AND MECHANICS', path: "testing", }),
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
            body: JSON.stringify({ title:  'TESTING AND MECHANICS', path: "testing", category: 3 }),
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