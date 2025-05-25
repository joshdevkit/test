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
                    <h1 class="text-success">Office Equipment</h1>
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
                                        <th>QTY</th>
                                        <th>Unit</th>
                                        <th>Name of Equipment</th>
                                        <th>Description/Specification</th>
                                        {{-- <th>Condition</th> --}}
                                        <th>Location/Room</th>
                                        <th>Date Delivered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($equipments as $equipment)
                                    <tr data-entry-id="{{ $equipment->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $equipment->items->count() }}</td>
                                        <td>{{ $equipment->unit }}</td>
                                        <td>{{ $equipment->item }}</td>
                                        <td>{{ $equipment->brand_description }}</td>
                                        {{-- <td></td> --}}
                                        <td>{{ $equipment->location }}</td>
                                        <td>{{ $equipment->date_delivered }}</td>

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

        fetch("{{ route('print-lab-equipment') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ data: rows, title: 'LABORATORY EQUIPMENT' }),
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
    document.getElementById("print-all-btn").addEventListener("click", function () {
    const dataTable = $('#example1').DataTable();

        const currentPageLength = dataTable.page.len();
        const currentPage = dataTable.page();

        dataTable.page.len(-1).draw();
        setTimeout(() => {
            let rows = [];
            document.querySelectorAll("#example1 tbody tr").forEach((row) => {
                let rowData = [];
                row.querySelectorAll("td").forEach((td) => {
                    rowData.push(td.innerText.trim());
                });
                rows.push(rowData);
            });

            fetch("{{ route('print-lab-equipment-all') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ data: rows, title: 'LABORATORY EQUIPMENT' }),
            })
            .then(response => response.blob())
            .then(blob => {
                let url = window.URL.createObjectURL(blob);
                window.open(url, "_blank");
            })
            .catch(error => console.error("Error:", error))
            .finally(() => {
                dataTable.page.len(currentPageLength).draw();
                dataTable.page(currentPage).draw('page');
            });
        }, 500);
    });
</script>
@endsection