<!DOCTYPE html>
<html>

<head>
    <title>Print {{ $title }}</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            text-align: center;
        }

        h1 {
            font-family: "Old English Text MT", serif;
            font-size: 22px;
        }

        h2 {
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            font-size: 12px;
        }

        table th {
            background-color: #f2f2f2;
        }

        /* HEADER STYLE */
        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header img {
            width: 80px;
            height: 80px;
            position: absolute;
            left: 200;
            top: -10;
        }


        .school-title {
            margin-top: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header-container">
        <div class="header">
            <img src="{{ public_path('dist/img/spup1.png') }}" alt="Logo">
            <div class="header-content">
                <h1>Saint Paul University Philippines</h1>
                <h3>Tuguegarao City, Cagayan 3500</h3>
            </div>
        </div>
    </div>

    <div class="school-title">
        <h4>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</h4>
        <h5><strong>OFFICE TRANSACTIONS</strong></h5>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Instructor</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Purpose</th>
                <th>Datetime Borrowed</th>
                <th>Status</th>
                <th>Days Not Returned</th>
                <th>Datetime Returned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->requested_by_name }}</td>
                <td>
                    {{
                    ($request->item_type == "Supplies") ? $request->supply_item_name :
                    $request->equipment_item_name
                    }}
                </td>
                <td>{{ $request->quantity_requested }}</td>
                <td>{{ $request->purpose }}</td>
                <td>{{ $request->created_at }}</td>
                <td>{{ $request->status }}</td>
                <td>
                    @if ($request->status == 'Pending')
                    {{ \Carbon\Carbon::parse($request->created_at)->diffInDays(now()) }} days
                    @else
                    N/A
                    @endif
                </td>
                <td>{{ $request->status == 'Returned' ? $request->updated_at : 'Not Returned' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>