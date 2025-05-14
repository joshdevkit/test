<!-- Sidebar Menu -->

<div class="sidebar" style="background-color: #383a3a;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-white flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Sidebar content -->
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboardo') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dashboardo') ? 'active' : '' }}"
                        href="{{ route('office.dashboardo') }}">
                        <i class="nav-icon fas fa-tachometer-alt" style="color: #ffff"></i>
                        <p class="text-light">Dashboard</p>
                    </a>
                </li>

                <!-- Transaction -->
                <li class="nav-item {{ request()->routeIs('transactions') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('office-admin.transactions') ? 'active' : '' }}"
                        href="{{ route('office-admin.transactions') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
                    </a>
                </li>

                <!-- Equipment -->


                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('supplies') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('office-supplies') ? 'active' : '' }}"
                        href="{{ route('office-supplies') }}">
                        <i class="nav-icon fas fa-clipboard" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Supplies') }}</p>
                    </a>
                </li>
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('site.equipment.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site.equipment.index') ? 'active' : '' }}"
                        href="{{ route('site.equipment.index') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Equipment') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('site.equipment-items.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site.equipment-items.index') ? 'active' : '' }}"
                        href="{{ route('site.equipment-items.index') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Equipment Items') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('office.requisition') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('office.requisition') ? 'active' : '' }}"
                        href="{{ route('office.requisition') }}">
                        <i class="nav-icon fas fa-laptop" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Requisition') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('site.reports') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site.reports') ? 'active' : '' }}"
                        href="{{ route('site.reports') }}">
                        <i class="nav-icon fas fa-chart-area" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Reports') }}</p>
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
    </div>
    </ul>
    </nav>
</div>
</div>
