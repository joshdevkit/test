<!-- Sidebar Menu -->

<div class="sidebar" style="background-color: #383a3a;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-dark flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Sidebar content -->
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                        href="{{ route('superadmin.dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('users') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                        href="{{ route('users.index') }}">
                        <i class="nav-icon fas fa-users" style="color: #ffff"></i>
                        <p class="text-light">{{ __('User Management') }}</p>
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

                <!-- Equipment -->
                <li class="nav-header text-light"> Engineering Laboratory</li>

                <li class="nav-item {{ request()->routeIs('superadmin.transaction.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.transaction.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.transaction.index') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
                    </a>
                </li>
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('computer_engineering') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.computer_engineering.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.computer_engineering.index') }}">
                        <i class="nav-icon fas fa-desktop" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Computer Engineering') }}</p>
                    </a>
                </li>
                <!-- Surveying -->
                <li class="nav-item {{ request()->routeIs('surveying') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.surveying.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.surveying.index') }}">

                        <i class="nav-icon fas fa-map-marked" style="color: #ffff"> </i>
                        <p class="text-light">
                            {{ __('Surveying') }}
                        </p>
                    </a>
                </li>
                <!-- Testing & Mechanics -->
                <li class="nav-item {{ request()->routeIs('testing') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.testing.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.testing.index') }}">

                        <i class="nav-icon fas fa-tools" style="color: #ffff"></i>
                        <p class="text-light">
                            Testing & Mechanics
                        </p>
                    </a>
                </li>
                <!-- General Construction -->
                <li class="nav-item {{ request()->routeIs('construction') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.construction.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.construction.index') }}">
                        <i class="nav-icon fas fa-hard-hat" style="color: #ffff"></i>
                        <p class="text-light">
                            General Construction
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('fluid') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.fluid.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.fluid.index') }}">
                        <i class="nav-icon fas fa-tint" style="color: #ffff"></i>
                        <p class="text-light">
                            Hydraulics and Fluids
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('superadmin.lab-items') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.lab-items') ? 'active' : '' }}"
                        href="{{ route('superadmin.lab-items') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">
                            Laboratory Items
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('auth.reports') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('auth.reports') ? 'active' : '' }}"
                        href="{{ route('auth.reports') }}">
                        <i class="nav-icon fas fa-chart-area" style="color: #ffff"></i>
                        <p class="text-light">
                            Laboratory Reports
                        </p>
                    </a>
                </li>

                <li class="nav-header text-light"> Site Office </li>
                <li class="nav-item {{ request()->routeIs('superadmin.transaction.site') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.transaction.site') ? 'active' : '' }}"
                        href="{{ route('superadmin.site-transactions.index') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('supplies') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.supplies.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.supplies.index') }}">
                        <i class="nav-icon fas fa-clipboard" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Supplies') }}</p>
                    </a>
                </li>
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('equipment') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.equipment.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.equipment.index') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Equipment') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('superadmin.site-equipment-items.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('superadmin.site-equipment-items.index') ? 'active' : '' }}"
                        href="{{ route('superadmin.site-equipment-items.index') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Office Equipment') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('site.reports') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site.reports') ? 'active' : '' }}"
                        href="{{ route('site.reports') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Office Reports') }}</p>
                    </a>
                </li>



            </ul>
        </nav>
        <style>
            .nav-pills .nav-link:hover {
                border-radius: 4px;
                color: #ffffff;

                .user-panel mt-3 pb-3 mb-3 d-flex {
                    background-color: #648e37;

                }


            }
        </style>

    </div>
    </ul>
    </nav>
</div>
</div>
