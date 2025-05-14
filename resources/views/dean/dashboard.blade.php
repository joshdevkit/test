@extends('layouts.dean')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Dashboard </h1>
                    </div>
                    <div class="col-sm-6">

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
                            'color' => 'bg-info',
                            'icon' => 'fas fa-laptop',
                            'route' => 'computer_engineering.index',
                        ],
                        'construction' => [
                            'title' => 'General Construction',
                            'color' => 'bg-success',
                            'icon' => 'fas fa-hard-hat',
                            'route' => 'construction.index',
                        ],
                        'surveying' => [
                            'title' => 'Surveying',
                            'color' => 'bg-warning',
                            'icon' => 'fas fa-map-marked-alt',
                            'route' => 'surveying.index',
                        ],
                        'testing' => [
                            'title' => 'Testing Mechanics',
                            'color' => 'bg-danger',
                            'icon' => 'fas fa-tools',
                            'route' => 'testing.index',
                        ],
                        'fluid' => [
                            'title' => 'Hydraulics and Fluids',
                            'color' => 'bg-info',
                            'icon' => 'fas fa-tint',
                            'route' => 'fluid.index',
                        ],
                        'supplies' => [
                            'title' => 'Supplies',
                            'route' => 'testing.index',
                            'icon' => 'fas fa-tools',
                            'color' => 'bg-info',
                            'bg' => 'bg-danger',
                        ],
                        'equipments' => [
                            'title' => 'Equipments',
                            'route' => 'testing.index',
                            'color' => 'bg-info',
                            'icon' => 'fas fa-tools',
                            'bg' => 'bg-danger',
                        ],
                        'transactions' => [
                            'title' => 'laboratory Transactions',
                            'route' => 'dean.transactions',
                            'color' => 'bg-info',
                            'icon' => 'fas fa-list',
                            'bg' => 'bg-danger',
                        ],
                        'office_transac' => [
                            'title' => 'Office Transactions',
                            'route' => 'dean.transactions.site',
                            'color' => 'bg-info',
                            'icon' => 'fas fa-list',
                            'bg' => 'bg-danger',
                        ],
                    ];
                @endphp

                <div class="row">
                    @foreach ($boxes as $key => $box)
                        <div class="col-lg-4  col-xs-12 col-md-6">
                            <!-- small card -->
                            <div class="small-box {{ $box['color'] }}">
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
                    {{-- <div class="col-lg-4 col-4 col-sm-12">
                        <!-- First Small Box for Transactions -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>150</h3>
                                <p>Transactions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div> --}}
                </div>

                <!-- Second Row for Transactions and Pie Charts -->
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Number of transactions (Laboratory)
                                </h3>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card card-default">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Number of transactions (Site Office)
                                </h3>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart2"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content -->
    </div>



    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('dean.requisitions.chart') }}",
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
                url: "{{ route('dean.office.chart') }}",
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
