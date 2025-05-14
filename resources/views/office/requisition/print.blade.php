<!DOCTYPE html>
<html>

<head>
    <title>Requisition Data / Print </title>
    <link rel="stylesheet" href="{{ asset('print.css') }}">
</head>

<body>
    <div style="border: 1px solid black !important; width: 6rem; padding: 3px;">
        UNIV-043 B
    </div>
    <div class="header">
        <img src="{{ asset('dist/img/spup1.png') }}" alt="Logo">
        <div class="header-content">
            <h1>Saint Paul University Philippines</h1>
            <h3>Tuguegarao City, Cagayan 3500</h3>
        </div>
    </div>
    <div class="school-title">
        <h4>BUSINESS AFFAIRS OFFICE</h4>
        <h2 style="margin-top: 35px"><strong>REQUISITION FOR SUPPLIES</strong></h2>
    </div>

    <div class="date-section">
        <div class="date-box">
            <p>SOURCE OF FUND: {{ $data->source_of_fund }}</p>
            <p>PURPOSE/PROJECT: {{ $data->purpose_project }}</p>
        </div>
        <div class="date-box">
            <p>{{ date('F d, Y h:i A', strtotime($data->created_at)) }}</p>
        </div>
    </div>

    <table>
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
        <tbody>
            @foreach ($data->items as $supply)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $supply->item_name }}</td>
                    <td>{{ $supply->unit_cost }}</td>
                    <td>{{ $supply->total }}</td>
                    <td>{{ $supply->purchase_order }}</td>
                    <td>{{ $supply->remarks }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    @php
        $dean = \App\Models\User::role('dean')->first();
    @endphp

    <div class="signature-section">
        <div class="signature">
            <p>Requested by: <img style="width: 250px" src="{{ asset($data->signature) }}"></p>
            <p class="has-underline"> {{ Auth::user()->name }}</p>
            <p class="margin-top: 0;">(Signature over Printed Name)</p>
        </div>

        <div class="signature">
            <p>Items Received per P.O________</p>
            <p>Date: ______________________</p>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature">
            <p>Endorse By: <img style="width: 250px;" src="{{ asset($data->dean_signature) }}"></p>
            <p class="has-underline">{{ $dean->name }}</p>
            <p class="margin-top: 0;">Department/Unit Head</p>

            <p>______________________</p>
            <p>Available Items Received by</p>
            <p>Date: _________________</p>

        </div>
        <div class="signature">
            <p>Approved by: <img style="width: 250px;"></p>
            <p>______________________</p>
            <p>VP for Finance</p>

            <p>______________________</p>
            <p>President</p>
        </div>
    </div>

    <div class="signature-section" style="border: 1px solid black !important; width: 70%; padding: 3px;">
        NOTE: Requests for supply/supplies need the approval of the President
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            window.print();
        })
    </script>
</body>

</html>
