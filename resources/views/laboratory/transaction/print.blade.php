<!DOCTYPE html>
<html>

<head>
    <title>Print Laboratory Transactions</title>
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

        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header img {
            width: 80px;
            height: 80px;
            position: absolute;
            left: 200px;
            top: -10px;
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
                <th>User Name</th>
                <th>Quantity</th>
                <th>Purpose</th>
                <th>Datetime Borrowed</th>
                <th>Status</th>
                <th>Days Not Returned</th>
                <th>Datetime Returned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requisitions as $requisition)
            <tr>
                <td>{{ $requisition->id }}</td>
                <td>{{ $requisition->instructor->name }}</td>
                <td>
                    {{ $requisition->items[0]->quantity ?? '' }}
                </td>
                <td>
                    {{ $requisition->activity }}
                </td>
                <td>
                    {{ date('F d, Y h:i A', strtotime($requisition->date_time_filed)) }}
                </td>
                <td>{{ $requisition->status }}
                </td>
                <td>
                    {{ $requisition->status === 'Returned' ? '' :
                    \Carbon\Carbon::parse($requisition->date_time_filed)->diffInDays(now())
                    . ' days' }}

                </td>

                <td>
                    {{ $requisition->status === 'Returned' ? date('F d, Y h:i A',
                    strtotime($requisition->returned_date)) : '' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>