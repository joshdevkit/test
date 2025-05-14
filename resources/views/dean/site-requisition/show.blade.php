@extends('layouts.dean')

@section('content')
    <style>
        .signature-canvas {
            border: 1px solid rgb(165, 163, 163);
            border-radius: 0.7em;
            height: 150px;
            width: 100%;
            cursor: crosshair;
        }
    </style>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class = "text-success">Site Office Requisitions</h1>
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
                                <h4>
                                    Requested by : {{ $data->user->name }}
                                    <p class="float-right text-md">Date requested:
                                        {{ date('F d, Y h:i A', strtotime($data->created_at)) }}
                                    </p>
                                </h4>
                                {{-- <div class="flex float-right">
                                    <button class="btn btn-primary btn-sm ml-2" id="print-btn">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </div> --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label>Source of Fund</label>
                                        <input readonly type="text" name="source_of_fund"
                                            value="{{ $data->source_of_fund }}" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Purpose/Project</label>
                                        <input readonly type="text" name="purpose_project"
                                            value="{{ $data->purpose_project }}" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">

                                    </div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="bg-secondary">
                                            <th>QUANTITY/UNIT</th>
                                            <th>ITEMS</th>
                                            <th>UNIT COST</th>
                                            <th>TOTAL</th>
                                            <th>PURCHASE ORDER #</th>
                                            <th>REMARKS</th>
                                        </tr>
                                    </thead>
                                    <tbody id="supplies-table">
                                        @foreach ($data->items as $supply_item)
                                            <tr>
                                                <td>
                                                    {{ $supply_item->id }}
                                                </td>
                                                <td>
                                                    {{ $supply_item->item_name }}</td>
                                                </td>
                                                <td>
                                                    {{ $supply_item->unit_cost }}
                                                </td>
                                                <td>
                                                    {{ $supply_item->total }}
                                                </td>
                                                <td>
                                                    {{ $supply_item->purchase_order }}
                                                </td>
                                                <td>
                                                    {{ $supply_item->remarks }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($data->status == 'Pending')
                                    <form action="{{ route('site-requisition.approve') }}" class="mt-4" method="POST">
                                        @csrf
                                        <input type="hidden" name="requisition_id" value="{{ $data->id }}">
                                        <div class="form-group" id="signt">
                                            <label for="signature">Signature</label>
                                            <canvas id="userSignature" class="signature-canvas"></canvas>
                                            <button type="button" class="btn btn-secondary text-white btn-sm mt-2"
                                                onclick="clearSignature('#userSignature')">Clear</button>
                                            <input type="hidden" id="signature" name="signature">
                                            <button type="submit"
                                                class="btn btn-success text-white btn-sm mt-2">Submit</button>
                                        </div>
                                    </form>
                                @else
                                    <p class="mt-5 text-success text-center">This Request has been approved.
                                        {{ $data->user->name }}
                                        has been notified.</p>
                                @endif
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
        $(document).ready(function() {
            setupSignatureCanvas('#userSignature', '#signature');
            $(document).on('click', '.decline', function() {
                $('.submit_decline').removeClass('d-none')
                $('.decline').addClass('d-none')
                $('#reason').removeClass('d-none')
                $('.approve').addClass('d-none')
            })
        });

        function setupSignatureCanvas(canvasId, inputId) {
            const $canvas = $(canvasId);
            const canvas = $canvas[0];
            const ctx = canvas.getContext('2d');
            let drawing = false;

            canvas.width = $canvas.width();
            canvas.height = $canvas.height();

            $canvas.on('mousedown touchstart', function(e) {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(getX(e), getY(e));
            });

            $canvas.on('mousemove touchmove', function(e) {
                if (drawing) {
                    ctx.lineTo(getX(e), getY(e));
                    ctx.strokeStyle = '#000';
                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.stroke();
                }
            });

            $canvas.on('mouseup touchend', function() {
                drawing = false;
                ctx.closePath();
                saveSignature(canvasId, inputId);
            });

            $canvas.on('mouseleave touchcancel', function() {
                drawing = false;
            });

            function getX(event) {
                return (event.pageX || event.originalEvent.touches[0].pageX) - $canvas.offset().left;
            }

            function getY(event) {
                return (event.pageY || event.originalEvent.touches[0].pageY) - $canvas.offset().top;
            }
        }

        function clearSignature(canvasId) {
            const canvas = $(canvasId)[0];
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function saveSignature(canvasId, inputId) {
            const canvas = $(canvasId)[0];
            const signatureData = canvas.toDataURL('image/png');
            $(inputId).val(signatureData);
        }
    </script>
@endsection
