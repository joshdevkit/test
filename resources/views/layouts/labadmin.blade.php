<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SITE Inventory System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('logo.png') }}" alt="Logo" height="60" width="60">
        </div>


        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>

            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        @php
                        $notifications = auth()->user()->unreadNotifications;
                        @endphp
                        @if ($notifications->count() > 0)
                        <span class="badge badge-warning navbar-badge">{{ $notifications->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notification_div">
                        <span class="dropdown-item dropdown-header">Notifications
                            ({{ $notifications->count() }})</span>
                        @forelse($notifications as $notification)
                        <div class="notification-item">
                            <a href="{{ route('borrows.show', ['id' => $notification->data['requisition_id']]) }}"
                                class="dropdown-item mark-as-read" data-id="{{ $notification->id }}">
                                <!-- Add data-id attribute -->
                                <i class="fas fa-envelope mr-2"></i>
                                {!! \Illuminate\Support\Str::limit($notification->data['activity'], 20, '...') !!} <br>
                                @if (!empty($notification->data['instructor_name']))
                                {{ 'By: ' . $notification->data['instructor_name'] }}
                                @else
                                {{ $notification->data['message'] }}
                                @endif
                                <span class="float-right text-muted text-sm">{{
                                    $notification->created_at->diffForHumans() }}</span>
                            </a>
                        </div>

                        @empty
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">No new notifications</a>
                        @endforelse
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-success elevation-4">
            <!-- Brand Logo -->

            <a href="{{ route('dashboard') }}" class="brand-link" style="background-color: #383a3a;">
                <div style="position: relative; background-color: #383a3a;">
                    <img src="{{ asset('dist/img/logo.png') }}" alt="logo" class="brand-image"
                        style="opacity: .8; position: absolute; top: 0; left: 0;">
                    <span class="brand-text text-light font-weight-bold" style="padding-left: 50px;">Inventory
                        System</span>
                </div>
            </a>

            <!-- END Brand Logo -->


            <!-- Sidebar Menu -->
            @include('partials.sidebar')
            <!-- /.sidebar-menu -->

        </aside>

        <!-- Content Wrapper. Contains page content -->

        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">

            <div class="container-fluid">
                @yield('content')
            </div>
        </section>

        <!-- /.content-wrapper -->
        <footer class="main-footer footer-success" style="background-color: #fffece;padding: 5px;">
            <div class="container">
                <div class="row align-items-center">
                    <img src="{{ asset('site.png') }}" alt="site" class="img-fluid rounded-circle"
                        style="max-width: 50px;">
                    <div class="text-dark" style="margin-left: 10px;">
                        School of Information Technology and Engineering
                    </div>
                </div>
            </div>
        </footer>




        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    @yield('scripts')

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": false, // Disable column reordering
            });

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $("#adminLteDataTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        $(document).on('click', '.mark-as-read', function(event) {
            event.preventDefault();

            var notificationId = $(this).data('id');
            var targetUrl = $(this).attr('href');

            $.ajax({
                url: '/notifications/' + notificationId + '/read',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    var badgeCount = $('.navbar-badge');
                    var currentCount = parseInt(badgeCount.text());
                    if (currentCount > 0) {
                        badgeCount.text(currentCount - 1);
                    }

                    window.location.href = targetUrl;
                },
                error: function(xhr) {
                    console.error('Failed to mark notification as read:', xhr);
                    window.location.href = targetUrl;
                }
            });
        });
    </script>
</body>

</html>