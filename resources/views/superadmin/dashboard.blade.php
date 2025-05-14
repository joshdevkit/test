@extends('layouts.superadmin')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Dashboard</h1>
                    </div>
                  
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @php
                    $boxes = [
                        'computer' => [
                            'title' => 'Computer Engineering',
                            'route' => 'computer_engineering.index',
                            'icon' => 'fas fa-laptop',
                            'bg' => 'bg-info',
                        ],
                        'construction' => [
                            'title' => 'General Construction',
                            'route' => 'construction.index',
                            'icon' => 'fas fa-hard-hat',
                            'bg' => 'bg-success',
                        ],
                        'surveying' => [
                            'title' => 'Surveying',
                            'route' => 'surveying.index',
                            'icon' => 'fas fa-map-marked-alt',
                            'bg' => 'bg-warning',
                        ],
                        'testing' => [
                            'title' => 'Testing Mechanics',
                            'route' => 'testing.index',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-danger',
                        ],
                        'supplies' => [
                            'title' => 'Supplies',
                            'route' => 'testing.index',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-warning',
                        ],
                        'equipments' => [
                            'title' => 'Equipments',
                            'route' => 'testing.index',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-info',
                        ],
                        'office_transaction' => [
                            'title' => 'Office Transaction',
                            'route' => 'superadmin.transaction.index',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-info',
                        ],
                        'laboratory_transaction' => [
                            'title' => 'Laboratory Transaction',
                            'route' => 'superadmin.site-transactions.index',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-info',
                        ],
                    ];
                @endphp

                <div class="row">
                    <!-- First Row for the main boxes -->
                    @foreach ($boxes as $key => $box)
                        <div class="col-lg-4  col-sm-12">
                            <!-- small card -->
                            <div class="small-box {{ $box['bg'] }}">
                                <div class="inner">
                                    <h3>{{ $totals[$key] }}</h3>
                                    <p>{{ $box['title'] }}</p>
                                </div>
                                <div class="icon">
                                    <i class="{{ $box['icon'] }}"></i>
                                </div>
                                <a href="{{ route($box['route']) }}" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach


                    <div class="col-lg-4  col-sm-12">
                        <!-- Second Small Box for Hydraulics and Fluids -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totals['fluid'] ?? 0 }}</h3>
                                <p>Hydraulics and Fluids</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tint"></i>
                            </div>
                            <a href="{{ route('fluid.index') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6  col-sm-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                Number of transactions (Laboratory)
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <canvas id="pieChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-lg-6  col-sm-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                Number of transactions (Site Office)
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <canvas id="pieChart2"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
    </div>
    <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('requisitions.chart') }}",
                method: 'GET',
                success: function(data) {
                    const labels = Object.keys(data);
                    const values = Object.values(data);

                    var ctx = $('#pieChart')[0].getContext('2d');
                    var pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Requisitions by Category',
                                data: values,
                                backgroundColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem
                                                .raw;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

            $.ajax({
                url: "{{ route('office.chart') }}",
                method: 'GET',
                success: function(data) {
                    const labels = Object.keys(data);
                    const values = Object.values(data);
                    var ctx = $('#pieChart2')[0].getContext('2d');
                    var pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Requisitions by Category',
                                data: values,
                                backgroundColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderColor: ['#FF5733', '#33A1FF', '#FFEB33',
                                    '#33FF57'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem
                                                .raw;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    </script>
@endsection
