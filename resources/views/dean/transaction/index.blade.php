@extends('layouts.dean')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="text-success">Transactions</h1>
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
                                        <td>{{
                                            optional(
                                            $requisition->items
                                            ->flatMap->serials
                                            ->first()
                                            ?->equipmentBelongs
                                            )->equipment ?? 'N/A'
                                            }}
                                        </td>
                                        <td>
                                            {{ $requisition->items->flatMap->serials->where('borrow_status',
                                            'Approved')->count() }}
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

        fetch("{{ route('dean-print-lab-transac') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: JSON.stringify({ data: rows, title: 'LABORATORY TRANSACTIONS' }),
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

            fetch("{{ route('dean-print-lab-transac-all') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ data: rows, title: 'LABORATORY TRANSACTIONS' }),
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