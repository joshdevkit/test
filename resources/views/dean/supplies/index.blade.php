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
                            <div class="flex float-right">
                                <button class="btn btn-primary btn-sm float-right" id="print-all-btn">
                                    <i class="fas fa-print"></i> Print All
                                </button>
                                <button class="btn btn-primary btn-sm float-right mr-2" id="print-btn">
                                    <i class="fas fa-print"></i> Print
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

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $supply)
                                    <tr data-entry-id="{{ $supply->id }}">

                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supply->items->count() }}</td>
                                        <td>{{ $supply->unit }}</td>
                                        <td>{{ $supply->item }}</td>
                                        <td>{{ $supply->brand_description }}</td>
                                        <td>{{ $supply->location }}</td>
                                        <td>{{ $supply->date_delivered }}</td>


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

        fetch("{{ route('dean-supplies-print') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ data: rows, title: 'OFFICE SUPPLIES' }),
        })
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            window.open(url, "_blank");
        })
        .catch(error => console.error("Error:", error));
    });

</script>
<script>
    $('#print-all-btn').on('click', function () {
                const table = $('#example1').DataTable();

                // 1. Set to show all entries (remove pagination)
                const currentPageLength = table.page.len();
                table.page.len(-1).draw();

                // 2. Wait for redraw to complete before cloning
                setTimeout(function () {
                    // Clone the table with full data
                    const originalTable = document.getElementById('example1');
                    const tableClone = originalTable.cloneNode(true);
                    tableClone.classList.remove('dataTable');
                    let rows = [];
                    tableClone.querySelectorAll("tbody tr").forEach((row) => {
                        let rowData = [];
                        row.querySelectorAll("td").forEach((td) => {
                            rowData.push(td.innerText.trim());
                        });
                        rows.push(rowData);
                    });

                    // Send to server
                    fetch("{{ route('dean-supplies-print-all') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({ data: rows, title: 'OFFICE SUPPLIES' }),
                    })
                    .then(response => response.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    })
                    .catch(error => console.error("Error:", error))
                    .finally(() => {
                        table.page.len(currentPageLength).draw();
                    });
                }, 500);
            });
</script>

@endsection