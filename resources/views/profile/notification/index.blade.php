
@extends('layouts.userapp')

@section('content')
<div class="content-wrapper"    >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid"  >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class = "text-success">Computer Engineering's List of Equipment</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right" >
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Computer Engineering</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
  <x-app-layout>
    <section class="content" >
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
               
              <a href="{{ route('computer_engineering.create') }}" class="btn btn-success btn-sm">
                  <i class="fas fa-plus"></i> Add Equipment
              </a>


              </div>
              <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bell"></i>
                    Notification
                </h3>
            </div>
            <div class="card-body">
                @foreach($notifications as $notification)
                    <div class="callout callout-info">
                        <h5>{{ $notification->data['message'] }}</h5>
                        <p>Received at: {{ $notification->created_at }}</p>
                        @if(!$notification->read_at)
                            <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-primary">Mark as Read</a>
                        @endif
                    </div>
                @endforeach
                <div class="text-right">
                    <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-sm btn-secondary">Mark All As Read</a>
                </div>
            </div>
        </div>
    </div>
</div>
          <!-- /.col -->
        

    </section>
  </x-app-layout>
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