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
                        <h1 class = "text-success">List of Equipment Items</h1>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Equipment</th>
                                    <th>Serial No</th>
                                    <th>Condition</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($equipments as $item)
                                    <tr>
                                        <td>{{ $item->equipment->category->name }}</td>
                                        <td>{{ $item->equipment->equipment }}</td>
                                        <td>{{ $item->serial_no }}</td>
                                        <td>{{ $item->condition }}</td>
                                        <td>
                                            <a href="{{ route('dean.laboratory-items-history', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-info"> <i class="fas fa-list"></i>
                                                History</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
