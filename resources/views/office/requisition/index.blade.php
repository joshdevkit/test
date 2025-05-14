@extends('layouts.officeadmin')

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
                        <h1 class = "text-success">Office Requisition</h1>
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
                        <h4>
                            REQUISITION FOR EQUIPMENT
                            <a class="btn btn-secondary float-right" href="{{ route('office.requisition.request') }}">Show
                                recent request</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('office.requisition.post') }}" method="POST"> @csrf <div
                                class="col-md-3 mb-2"> <label>Source of Fund</label> <input type="text"
                                    name="source_of_fund" id="source_of_fund" class="form-control"> </div>
                            <div class="col-md-3 mb-3"> <label>Purpose/Project</label> <input type="text"
                                    name="purpose_project" id="purpose_project" class="form-control"> </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="bg-secondary">
                                        <th>QUANTITY/UNIT</th>
                                        <th>ITEMS</th>
                                        <th>UNIT COST</th>
                                        <th>TOTAL</th>
                                        <th>PURCHASE ORDER #</th>
                                        <th>REMARKS</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="supplies-table">
                                    @foreach ($supplies as $items)
                                        <tr>
                                            <td><input class="form-control quantity" name="item_quantity[]" type="number">
                                            </td>
                                            <td><input type="hidden" name="item_name[]"
                                                    value="{{ $items->item }}">{{ $items->item }}</td>
                                            <td><input class="form-control unit-cost" type="text" name="unit_cost[]">
                                            </td>
                                            <td><input class="form-control total" type="number" name="total[]"></td>
                                            <td><input class="form-control purchase-order" type="text"
                                                    name="purchase_order[]">
                                            </td>
                                            <td><input class="form-control remarks" type="text" name="remarks[]"></td>
                                            @if (!in_array($items->item, $supplies->pluck('item')->toArray()))
                                                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i
                                                            class="fas fa-trash"></i></button></td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end align-items-center"> <button type="button"
                                    id="add-row" class="btn btn-secondary float-right mr-3">Add Item</button> <button
                                    type="submit" id="submit-btn" class="btn btn-success float-right">Submit</button>
                            </div>
                            <div class="form-group" id="signt">
                                <label for="signature">Signature</label>
                                <canvas id="userSignature" class="signature-canvas"></canvas>
                                <button type="button" class="btn btn-secondary text-white btn-sm mt-2"
                                    onclick="clearSignature('#userSignature')">Clear</button>
                                <input type="hidden" id="signature" name="signature">
                                <button type="submit" class="btn btn-success text-white btn-sm mt-2">Submit</button>
                            </div>

                        </form>
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

            function validateForm() {
                let isValid = true;
                $('#supplies-table tr').each(function() {
                    const $quantity = $(this).find('.quantity');
                    const $unitCost = $(this).find('.unit-cost');
                    const $total = $(this).find('.total');
                    const $purchaseOrder = $(this).find('.purchase-order');
                    const $remarks = $(this).find('.remarks');
                    const $source_of_fund = $('#source_of_fund')
                    const $purpose_project = $('#purpose_project')

                    if ($quantity.val() < 1) {
                        isValid = false;
                        $quantity.addClass('is-invalid');
                    } else {
                        $quantity.removeClass('is-invalid');
                        $quantity.addClass('is-valid');
                    }
                    if (!$unitCost.val()) {
                        isValid = false;
                        $unitCost.addClass('is-invalid');
                    } else {
                        $unitCost.removeClass('is-invalid');
                        $unitCost.addClass('is-valid');

                    }
                    if (!$total.val()) {
                        isValid = false;
                        $total.addClass('is-invalid');
                    } else {
                        $total.removeClass('is-invalid');
                        $total.addClass('is-valid');

                    }
                    if (!$purchaseOrder.val()) {
                        isValid = false;
                        $purchaseOrder.addClass('is-invalid');
                    } else {
                        $purchaseOrder.removeClass('is-invalid');
                        $purchaseOrder.addClass('is-valid');
                    }
                    if (!$remarks.val()) {
                        isValid = false;
                        $remarks.addClass('is-invalid');
                    } else {
                        $remarks.removeClass('is-invalid');
                        $remarks.addClass('is-valid');
                    }

                    if (!$source_of_fund.val()) {
                        isValid = false;
                        $source_of_fund.addClass('is-invalid');
                    } else {
                        $source_of_fund.removeClass('is-invalid');
                        $source_of_fund.addClass('is-valid');
                    }
                    if (!$purpose_project.val()) {
                        isValid = false;
                        $purpose_project.addClass('is-invalid');
                    } else {
                        $purpose_project.removeClass('is-invalid');
                        $purpose_project.addClass('is-valid');
                    }
                });
                $('#submit-btn').prop('disabled', !isValid);
            }
            $('#add-row').on('click', function() {
                const newRow = `
                <tr style="display: none;">
                    <td>
                        <input class="form-control quantity" name="item_quantity[]" type="number">
                    </td>
                    <td>
                        <input class="form-control" type="text" name="item_name[]">
                    </td>
                    <td>
                        <input class="form-control unit-cost" type="text" name="unit_cost[]">
                    </td>
                    <td>
                        <input class="form-control total" type="number" name="total[]">
                    </td>
                    <td>
                        <input class="form-control purchase-order" type="text" name="purchase_order[]">
                    </td>
                    <td>
                        <input class="form-control remarks" type="text" name="remarks[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;

                const $newRow = $(newRow).appendTo('#supplies-table').fadeIn(400);
                validateForm();
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').fadeOut(400, function() {
                    $(this).remove();
                    validateForm();
                });
            });
            $(document).on('input', '.quantity, .unit-cost, .total, .purchase-order, .remarks', function() {
                validateForm();
            });
            validateForm();
        });
    </script>
@endsection
