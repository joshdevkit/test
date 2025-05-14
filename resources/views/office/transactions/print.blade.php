<!DOCTYPE html>
<html>

<head>
    <title>Print Office Transactions</title>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }

            body {
                font-family: "Times New Roman", serif;
            }

            h1,
            h2,
            h3 {
                text-align: center;
            }

            h1 {
                font-family: "Old English Text MT", serif;
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
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 80px;
            height: 80px;
            margin-right: 10px;
        }

        .header-content {
            text-align: center;
        }

        .header-content h1 {
            font-weight: normal;
            font-family: "Old English Text MT", serif;
            margin: 0;
        }

        .header-content h3 {
            font-family: "Times New Roman", serif;
            margin: 0;
        }

        .school-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('dist/img/spup1.png') }}" alt="Logo">
        <div class="header-content">
            <h1>Saint Paul University Philippines</h1>
            <h3>Tuguegarao City, Cagayan 3500</h3>
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
                        {{ $request->item_name }} - ({{ $request->serial_no }})
                        @if ($request->item_type == 'Equipments' && $request->quantity_requested > 1)
                            @foreach (range(1, $request->quantity_requested - 1) as $i)
                                , {{ $request->item_name }} -
                                ({{ str_pad($request->serial_no + $i, 12, '0', STR_PAD_LEFT) }})
                            @endforeach
                        @elseif ($request->item_type == 'Supplies' && $request->quantity_requested > 1)
                            @foreach (range(1, $request->quantity_requested - 1) as $i)
                                , {{ $request->item_name }} -
                                ({{ str_pad($request->serial_no + $i, 12, '0', STR_PAD_LEFT) }})
                            @endforeach
                        @endif
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
