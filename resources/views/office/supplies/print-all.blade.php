<html>
    <head>
        <title>Print Data</title>
        <style>
            body {
                font-family: "Times New Roman", serif;
            }

            h1, h2, h3 {
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

            table th, table td {
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
        <!-- Fully Centered Header (Logo + Text) -->
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
            <h5><strong>INVENTORY OF {{ $title }}</strong></h5>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Equipment</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Date Delivered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row[0] }}</td>
                        <td>{{ $row[1] }}</td>
                        <td>{{ $row[2] }}</td>
                        <td>{{ $row[3] }}</td>
                        <td>{{ $row[4] }}</td>
                        <td>{{ $row[5] }}</td>
                        <td>{{ $row[6] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
