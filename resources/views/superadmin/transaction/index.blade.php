@extends('layouts.labadmin')

@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class = "text-success">Transactions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Transactions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
               
          

              </div>
              <!-- /.card-header -->
              <div class="card-body">
               
   
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Purpose</th>
                <th>Datetime Borrowed</th>
                <th>Status</th>
                <th>Days Not Returned</th>
                <th>Datetime Returned</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          @foreach($teacherborrows as $teacherborrow)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $teacherborrow->user_name }}</td>
                    <td>{{ $teacherborrow->item }}</td>
                    <td>{{ $teacherborrow->quantity }}</td>
                    <td>{{ $teacherborrow->purpose }}</td>
                    <td>{{ $teacherborrow->datetime_borrowed }}</td>
                    <td>{{ $teacherborrow->status }}</td>
                    <td>{{ $teacherborrow->days_not_returned }}</td>
                    <td>{{ $teacherborrow->datetime_returned }}</td>
                    <td>
                        @if($teacherborrow->status == 'waiting for approval')
                            <form action="{{ route('laboratory.transaction.approve', $teacherborrow->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            <form action="{{ route('laboratory.transaction.disapprove', $teacherborrow->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                            </form>
                        @elseif($transaction->status == 'approved')
                            <form action="{{ route('laboratory.transaction.returned', $teacherborrow->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Returned</button>
                            </form>
                            <form action="{{ route('laboratory.transaction.damaged', $teacherborrow->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Damaged</button>
                            </form>
                        @endif
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
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
      
    </section>
    <!-- /.content -->
  </div>





  <style>
  /* Pagination */


  /* Search bar */
  .dataTables_filter input {
    border-color: green !important;
  }

  /* Info text */
  .dataTables_info {
    color: green !important;
  }

  /* DataTables Buttons */
  .buttons-copy, .buttons-csv, .buttons-excel, .buttons-pdf, .buttons-print, .buttons-colvis {
    background-color: green !important;
    color: white !important;
  }

  .buttons-copy:hover, .buttons-csv:hover, .buttons-excel:hover, .buttons-pdf:hover, .buttons-print:hover, .buttons-colvis:hover {
    background-color: darkgreen !important;
    color: white !important;
  }
  
  @media print {
            @page {
              size: A4 landscape;
            margin: 20mm;
            @top-center {
                content: "Saint Paul University Philippines - Inventory Report";
            }
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
            }
        
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }

        .container {
            text-align: center;
            margin-top: 20px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            display: none;
        }

        @media print {
            .btn {
                display: none !important;
            }

            .container {
                text-align: center;
                margin-top: 0;
            }

            .title {
                font-size: 16px;
                font-weight: bold;
            }

            .subtitle {
                font-size: 14px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .dt-buttons {
                display: none !important;
            }
        }}
    </style>
</style>





<!-- jQuery UI -->
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- fullCalendar 2.2.5 -->
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/fullcalendar/main.js"></script>
<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

@endsection