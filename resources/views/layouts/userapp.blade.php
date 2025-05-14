<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <style>
        /* Add spacing between notifications */
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f4f4f4;
        }

        /* Reduce the padding for the notification icon */
        .notification-item .mr-3 {
            margin-right: 10px;
        }

        /* Ensure the notification text doesn't overflow or wrap incorrectly */
        .notification-item .font-weight-bold {
            white-space: pre-wrap;
            /* This ensures that the text wraps correctly if it's long */
            word-wrap: break-word;
        }

        /* Adjust the overall notification box size to avoid crowding */
        .notification_div {
            max-height: 400px;
            /* Set a max height */
            overflow-y: auto;
            /* Add scrolling if content exceeds */
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-light bg-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/dashboard" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        @php
                        $notifications = auth()->user()->unreadNotifications()->latest()->limit(5)->get();
                        @endphp
                        @if ($notifications->count() > 0)
                        <span class="badge badge-warning navbar-badge" id="notification-count">{{
                            $notifications->count() }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right notification_div">
                        <span class="dropdown-item dropdown-header">Notifications ({{ $notifications->count() }})</span>

                        @forelse($notifications as $notification)
                        <div class="notification-item" id="notification-{{ $notification->id }}">
                            <a href="#" class="dropdown-item mark-as-read" data-id="{{ $notification->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="mr-3">
                                        <i class="fas fa-bell text-info"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold d-block">{!!
                                            nl2br(e($notification->data['message'] ?? $notification->data['status']))
                                            !!}</span>
                                        <div class="text-muted text-sm">
                                            {{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">No new notifications</a>
                        @endforelse
                    </div>


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
        <aside class="main-sidebar sidebar-light-success elevation-4">

            <a href="{{ route('dashboard') }}" class="brand-link" style="background-color: #383a3a;">
                <div style="position: relative; background-color: #383a3a;">
                    <img src="{{ asset('logo.png') }}" alt="logo" class="brand-image"
                        style="opacity: .8; position: absolute; top: 0; left: 0;">
                    <span class="brand-text text-light font-weight-bold" style="padding-left: 50px;">Inventory
                        System</span>
                </div>
            </a>

            <!-- END Brand Logo -->


            <!-- Sidebar Menu -->
            @include('partials.sidebaruser')
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
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
    <!-- date-range-picker -->
    <style>
        .btn-group .btn {
            border-radius: 25;
            /* Example to remove border-radius */
        }
    </style>
    @yield('sctipts')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        $(document).on('click', '.mark-as-read', function(e) {
            e.preventDefault();

            var notificationId = $(this).data('id');
            var notificationItem = $('#notification-' + notificationId);

            $.ajax({
                url: '/notifications/' + notificationId + '/mark-as-read',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    notificationItem.remove();

                    var currentCount = parseInt($('#notification-count').text());
                    $('#notification-count').text(currentCount - 1);

                    if (currentCount - 1 <= 0) {
                        $('#notification-count').remove();
                    }
                },
                error: function(xhr) {
                    console.error('Error marking notification as read:', xhr);
                }
            });
        });
    </script>
</body>

</html>