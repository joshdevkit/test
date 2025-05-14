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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="text-success">Transactions </h2>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
                <div class="card">
                    <div class="card-header py-20 bg-yellow-500">
                        <h1 class="text-white text-center">This request was made at
                            {{ date('F d, Y h:i A', strtotime($data->date_time_filed)) }}</h1>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-10 mt-3 col-md-12">
                            @php
                                $requisitionStatus = $data->status;
                            @endphp
                            @if ($requisitionStatus == 'Accepted by Dean')
                                <p>You Approved this requisitions.</p>
                            @elseif($requisitionStatus != 'Accepted by Dean')
                                <form action="{{ route('dean.borrows.update', ['id' => $data->id]) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group" id="signt">
                                        <label for="signature">Signature</label>
                                        <canvas id="userSignature" class="signature-canvas"></canvas>
                                        <button type="button" class="btn btn-secondary text-white btn-sm mt-2"
                                            onclick="clearSignature('#userSignature')">Clear</button>
                                        <input type="hidden" id="signature" name="signature">
                                    </div>
                                    <div id="reason" class="form-group d-none">
                                        <label for="">Feedback for Decline</label>
                                        <textarea name="feedback" rows="3" class="form-control"></textarea>
                                    </div>
                                    <div class="d-flex">
                                        <input type="hidden" name="category" value="{{ $data->category->name }}">
                                        <input type="hidden" name="requisition_id" id="requisition_id"
                                            value="{{ $data->id }}">
                                        <button type="submit"
                                            class="btn btn-success  text-white mr-3 approve">Approve</button>

                                    </div>
                                </form>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <h4>Requisition Details</h4>
                                <ul>
                                    <li><strong>Category:</strong> {{ $data->category->name }}</li>
                                    <li><strong>Date Filed:</strong>
                                        {{ date('F d, Y h:i A', strtotime($data->date_time_filed)) }}</li>
                                    <li><strong>Date Needed:</strong>
                                        {{ date('F d, Y h:i A', strtotime($data->date_time_needed)) }}</li>
                                    <li><strong>Instructor:</strong> {{ $data->instructor->name }}</li>
                                    <li><strong>Subject:</strong> {{ $data->subject }}</li>
                                    <li><strong>Course/Year:</strong> {{ $data->course_year }}</li>
                                    <li><strong>Activity:</strong> {{ $data->activity }}</li>
                                    <li><strong>Status:</strong> {{ $data->status }}</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h4><strong>Students</strong></h4>
                                @if ($data['students']->isNotEmpty())
                                    <ul>
                                        @foreach ($data['students'] as $student)
                                            <li>{{ $student->student_name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No students found.</p>
                                @endif
                            </div>


                        </div>
                        <a type="cancel" class="btn btn-danger float-right"
                            href="{{ url('/dean/transactions') }}">{{ __('Exit') }}</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($data['items']->isNotEmpty())
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th>Equipment</th>
                                        <th>Description</th>
                                        <th>Brand</th>
                                        <th>Condition during borrow</th>
                                        <th>Product Serial</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->items as $item)
                                        @foreach ($item->serials as $serial)
                                            @if ($serial->borrow_status === 'Approved')
                                                <tr>
                                                    <td>{{ $serial->equipmentBelongs->equipment ?? 'N/A' }}</td>
                                                    <td>{{ $serial->serialRelatedItem->description ?? 'N/A' }}</td>
                                                    <td>{{ $serial->equipmentBelongs->brand ?? 'N/A' }}</td>
                                                    <td>{{ $serial->condition_during_borrow ?? 'N/A' }}</td>
                                                    <!-- FIXED THIS LINE -->
                                                    <td>{{ $serial->serialRelatedItem->serial_no ?? 'N/A' }}</td>
                                                    <td>{{ $serial->borrow_status }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach

                                </tbody>
                            </table>
                        @else
                            <p>No items found.</p>
                        @endif
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
