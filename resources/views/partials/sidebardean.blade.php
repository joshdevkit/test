<!-- Sidebar Menu -->

<div class="sidebar" style="background-color: #383a3a;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-dark flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Sidebar content -->
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dean.dashboard') ? 'active' : '' }}"
                        href="{{ route('dean.dashboard') }}">
                        <i class="nav-icon fas fa-tachometer-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Dashboard') }}</p>
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

                <!-- Transaction -->


                <!-- Equipment -->
                <li class="nav-header text-light"> Equipment</li>
                <li class="nav-item {{ request()->routeIs('dean.transactions') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dean.transactions') ? 'active' : '' }}"
                        href="{{ route('dean.transactions') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
                    </a>
                </li>
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('computer_engineering') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('computer_engineering') ? 'active' : '' }}"
                        href="{{ route('computer_engineering.index') }}">
                        <i class="nav-icon fas fa-desktop" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Computer Engineering') }}</p>
                    </a>
                </li>
                <!-- Surveying -->
                <li class="nav-item {{ request()->routeIs('surveying') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('surveying.index') ? 'active' : '' }}"
                        href="{{ route('surveying.index') }}">

                        <i class="nav-icon fas fa-map-marked" style="color: #ffff"> </i>
                        <p class="text-light">
                            {{ __('Surveying') }}
                        </p>
                    </a>
                </li>
                <!-- Testing & Mechanics -->
                <li class="nav-item {{ request()->routeIs('testing') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('testing.index') ? 'active' : '' }}"
                        href="{{ route('testing.index') }}">

                        <i class="nav-icon fas fa-tools" style="color: #ffff"></i>
                        <p class="text-light">
                            Testing & Mechanics
                        </p>
                    </a>
                </li>
                <!-- General Construction -->
                <li class="nav-item {{ request()->routeIs('construction') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('construction.index') ? 'active' : '' }}"
                        href="{{ route('construction.index') }}">
                        <i class="nav-icon fas fa-hard-hat" style="color: #ffff"></i>
                        <p class="text-light">
                            General Construction
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('fluid') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('fluid.index') ? 'active' : '' }}"
                        href="{{ route('fluid.index') }}">
                        <i class="nav-icon fas fa-tint" style="color: #ffff"></i>
                        <p class="text-light">
                            Hydraulics and Fluids
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('dean.laboratory-items') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dean.laboratory-items') ? 'active' : '' }}"
                        href="{{ route('dean.laboratory-items') }}">
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
                        <p class="text-light">{{ __('Laboratory Reports') }}</p>
                    </a>
                </li>

                <li class="nav-header text-light"> Site Office </li>

                <li class="nav-item {{ request()->routeIs('dean.transactions.site') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dean.transactions.site') ? 'active' : '' }}"
                        href="{{ route('dean.transactions.site') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('supplies') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('supplies.index') ? 'active' : '' }}"
                        href="{{ route('supplies.index') }}">
                        <i class="nav-icon fas fa-clipboard" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Supplies') }}</p>
                    </a>
                </li>
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('equipment') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('equipment.index') ? 'active' : '' }}"
                        href="{{ route('equipment.index') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Equipment') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('dean.equipment-items') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dean.equipment-items') ? 'active' : '' }}"
                        href="{{ route('dean.equipment-items') }}">
                        <i class="nav-icon fas fa-wrench" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Equipment Items') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('site-requisition.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site-requisition.index') ? 'active' : '' }}"
                        href="{{ route('site-requisition.index') }}">
                        <i class="nav-icon fas fa-laptop" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Requisition') }}</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('site.reports') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('site.reports') ? 'active' : '' }}"
                        href="{{ route('site.reports') }}">
                        <i class="nav-icon fas fa-chart-area" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Office Reports') }}</p>
                    </a>
                </li>



            </ul>
        </nav>


    </div>
    </ul>
    </nav>
</div>
</div>
