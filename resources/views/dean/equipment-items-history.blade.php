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
                        <h1 class = "text-success">Laboratory Equipment Items History</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <a class="float-right btn btn-danger btn-sm" href="#"
                            onclick="history.back(); return false;">Exit</a>
                    </div>


                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Equipment Serial</th>
                                    <th>Borrowed By</th>
                                    <th>Date Borrowed</th>
                                    <th>Date Returned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $item)
                                    <tr>
                                        <td>{{ $item->serialRelatedItem->serial_no }}</td>
                                        <td>{{ $item->requisition->requisitions->instructor->name }}</td>
                                        <td>{{ date('F d, Y h:i A', strtotime($item->created_at)) }}</td>
                                        <td>
                                            @if ($item->borrow_status === 'Returned')
                                                {{ date('F d, Y h:i A', strtotime($item->returned_at)) ?? '' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
