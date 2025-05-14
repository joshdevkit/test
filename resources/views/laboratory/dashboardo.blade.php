@extends('layouts.labadmin')

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
                        'computer' => 'ComputerEngineering',
                    ];
                @endphp

                @foreach ($boxes as $key => $title)
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totals[$key] }}</h3>

                                    <p>Computer Engineering</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-laptop"></i>
                                </div>
                                <a href="{{ url('/laboratory-computer-engineering') }}"class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                @endforeach
                @php
                    $boxes = [
                        'construction' => 'Construction',
                    ];
                @endphp

                @foreach ($boxes as $key => $title)
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totals[$key] }}<sup style="font-size: 20px"></sup></h3>

                                <p>General Contruction</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                            <a href="{{ url('/constructions') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
                @php
                    $boxes = [
                        'surveying' => 'Surveying',
                    ];
                @endphp

                @foreach ($boxes as $key => $title)
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totals[$key] }}</h3>

                                <p>Surveying</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <a href="{{ url('/surveyings') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach

                @php
                    $boxes = [
                        'testing' => 'Testing',
                    ];
                @endphp

                @foreach ($boxes as $key => $title)
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small card -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $totals[$key] }}</h3>

                                <p>Testing Mechanics</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tools"></i>
                            </div>
                            <a href="{{ url('/testings') }}" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->
            </div>
            @endforeach



            <div class="row">
                <div class="col-md-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                Latest Transactions
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Name</th>
                                        <th>Quantity</th>
                                        <th>Purpose</th>
                                        <th>Datetime Borrowed</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requisitions as $requisition)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $requisition->instructor->name }}</td>
                                            <td>
                                                {{ $requisition->items[0]->quantity }}
                                            </td>
                                            <td>
                                                {{ $requisition->activity }}
                                            </td>
                                            <td>{{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                                            </td>
                                            <td>{{ $requisition->status }}
                                            </td>
                                            <td class="d-flex flex-auto">
                                                <a class="btn btn-primary btn-sm"
                                                    href="{{ route('borrows.show', ['id' => $requisition->id]) }}"><i
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
                <div class="col-md-6">
                    <!-- small card -->
                    <div class="container">
                        <div class="row">
                            <!-- First Small Box -->
                            <div class="col">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>150</h3>
                                        <p>Transactions</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                    <a href="{{ url('/laboratory/transaction') }}" class="small-box-footer">
                                        More info <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            @php
                                $boxes = [
                                    'fluid' => 'FLuid',
                                ];
                            @endphp

                            @foreach ($boxes as $key => $title)
                                <div class="col">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ $totals[$key] }}</h3>
                                            <p>Hydraulics and Fluids</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-tint"></i>
                                        </div>
                                        <a href="{{ url('/fluids') }}" class="small-box-footer">
                                            More info <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-wrench"></i>
                                Transactions (Laboratory)
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart2"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
        </section>
    </div>


    <!-- jQuery -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->

    <script>
        $(document).ready(function() {

            $.ajax({
                url: "{{ route('laboratory-office.chart') }}",
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
