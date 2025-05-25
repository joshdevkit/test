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
                    <h1 class="text-success">Office Supplies</h1>
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
                            <a class="btn btn-success btn-sm" href="{{ route('admin.supplies.create') }}">
                                <i class="fas fa-plus"></i> Add items</a>
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
                                        <th>QTY</th>
                                        <th>Unit</th>
                                        <th>Name of Equipment</th>
                                        <th>Description/Specification</th>
                                        <th>Location/Room</th>
                                        <th>Date Delivered</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $supply)
                                    <tr data-entry-id="{{ $supply->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supply->quantity }}</td>
                                        <td>{{ $supply->unit }}</td>
                                        <td>{{ $supply->item }}</td>
                                        <td>{{ $supply->brand_description }}</td>
                                        <td>{{ $supply->location }}</td>
                                        <td>{{ $supply->date_delivered }}</td>
                                        <td>
                                            <a class="btn btn-secondary btn-sm"
                                                href="{{ route('admin.supplies.show', ['id' => $supply->id]) }}">View
                                                More</a>
                                            <a class="btn btn-info btn-sm"
                                                href="{{ route('admin.supplies.edit', ['id' => $supply->id]) }}">Edit</a>
                                            <form id="delete-form-{{ $supply->id }}"
                                                action="{{ route('admin.supplies.destroy', ['id' => $supply->id]) }}"
                                                method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmDelete({{ $supply->id }})">
                                                    Delete
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete(id) {
                                                            if (confirm('Are you sure you want to delete this item?')) {
                                                                document.getElementById('delete-form-' + id).submit();
                                                            }
                                                        }
                                            </script>


                                        </td>

                                        @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('Data Empty') }}</td>
                                    </tr>
                                    @endforelse

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

        fetch("{{ route('print-supplies') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ data: rows, path: "supplies", title: 'OFFICE SUPPLIES' }),
        })
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            window.open(url, "_blank");
        })
        .catch(error => console.error("Error:", error));
    });

    document.getElementById("print-all-btn").addEventListener("click", function () {
        fetch("{{ route('print-all-supplies') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ path: "supplies", title: 'OFFICE SUPPLIES' }),
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