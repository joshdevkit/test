<!-- Sidebar Menu -->

<div class="sidebar" style="background-color: #383a3a;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-white flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-header text-light"> SITE Office</li>
                <li class="nav-item {{ request()->routeIs('office_user') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('office_user.create') ? 'active' : '' }}"
                        href="{{ route('office_user.create') }}">
                        <i class="nav-icon fas fa-clipboard" style="color: #ffff"></i>
                        <p class="text-light">Office Requisition</p>
                    </a>
                </li>

                <li class="nav-header text-light"> Engineering Laboratory</li>
                <li class="nav-item {{ request()->routeIs('teachersborrow') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('teachersborrow.create') ? 'active' : '' }}"
                        href="{{ route('teachersborrow.create') }}">
                        <i class="nav-icon fas fa-laptop" style="color: #ffff"></i>
                        <p class="text-light">Laboratory Requisition</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('change-password') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('change-password.index') ? 'active' : '' }}"
                        href="{{ route('change-password.index') }}">
                        <i class="nav-icon fas fa-cog" style="color: #ffff"></i>
                        <p class="text-light">
                            Change Password
                        </p>
                    </a>
                </li>




            </ul>
        </nav>
        <style>
            .nav-pills .nav-link:hover {
                border-radius: 2px;

                .user-panel mt-3 pb-3 mb-3 d-flex {
                    background-image: #648e37;
                }
            }
        </style>

    </div>
    </ul>
    </nav>
</div>
</div>
