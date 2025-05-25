@extends('layouts.superadmin')

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
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Equipment</th>
                                        <th>Purpose</th>
                                        <th>Datetime Borrowed</th>
                                        <th>Status</th>
                                        <th>Days Not Returned</th>
                                        <th>Datetime Returned</th>
                                        @hasrole('laboratory')
                                        <th>Actions</th>
                                        @endhasrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $requisition)
                                    @foreach ($requisition->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $requisition->instructor->name }}</td>
                                        <td>
                                            @foreach ($item->serials as $serial)
                                            {{ $serial->serialRelatedItem->serial_no }} -
                                            {{ $serial->equipmentBelongs->equipment }}<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $requisition->activity }}
                                        </td>
                                        <td>{{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                                        </td>
                                        <td>{{ $requisition->status }}
                                        </td>
                                        <td>
                                            {{ $requisition->status === 'Returned' ? '' :
                                            \Carbon\Carbon::parse($requisition->date_time_filed)->diffInDays(now()) . '
                                            days' }}

                                        </td>
                                        <td>
                                            {{ $requisition->status === 'Returned' ? date('F d, Y h:i A',
                                            strtotime($requisition->returned_date)) : '' }}
                                        </td>
                                        @hasrole('laboratory')
                                        <td class="d-flex flex-auto">
                                            @if ($requisition->status != 'Approved and Prepared')
                                            <div class="dropdown">
                                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                                    data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> Configure
                                                </a>
                                                <div class="dropdown-menu">
                                                    @if ($requisition->status === 'Pending')
                                                    <a class="dropdown-item status-option" data-status="Approved"
                                                        data-id="{{ $requisition->id }}"
                                                        href="{{ route('borrows.show', ['id' => $requisition->id]) }}">Approved</a>
                                                    <a class="dropdown-item status-option" data-status="Declined"
                                                        data-id="{{ $requisition->id }}"
                                                        href="{{ route('borrows.show', ['id' => $requisition->id]) }}">Declined</a>
                                                    @elseif ($requisition->status === 'Approved')
                                                    <a class="dropdown-item status-option" data-status="Received"
                                                        data-id="{{ $requisition->id }}" href="#">Received</a>
                                                    @elseif($requisition->status == 'Accepted by Dean')
                                                    <a class="dropdown-item status-option" data-status="Returned"
                                                        data-id="{{ $requisition->id }}" href="#">Returned</a>
                                                    <a class="dropdown-item status-option" data-status="Damaged"
                                                        data-id="{{ $requisition->id }}" href="#">Damaged</a>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        @elsehasrole('superadmin')
                                        @endhasrole
                                    </tr>
                                    @endforeach
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
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

        fetch("{{ route('admin-print-lab-equipment') }}", {
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

            fetch("{{ route('admin-print-lab-equipment-all') }}", {
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