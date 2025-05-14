<!-- Sidebar Menu -->

<div class="sidebar" style="background-color: #383a3a;">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-dark flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Sidebar content -->
                <!-- Dashboard -->
                <li class="nav-item {{ request()->routeIs('dashboardo') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('dashboardo') ? 'active' : '' }}"
                        href="{{ route('laboratory.dashboardo') }}">
                        <i class="nav-icon fas fa-tachometer-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Dashboard') }}</p>
                    </a>
                </li>


                <!-- Transaction -->
                <li
                    class="nav-item {{ request()->routeIs('transaction') || request()->routeIs('borrows.show') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('transaction.index') || request()->routeIs('borrows.show') ? 'active' : '' }}"
                        href="{{ route('transaction.index') }}">
                        <i class="nav-icon fas fa-exchange-alt" style="color: #ffff"></i>
                        <p class="text-light">{{ __('Transactions') }}</p>
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
                <li class="nav-header text-light"> Equipment</li>

                {{-- @php
                    $categories = \App\Models\Category::all();
                    $routes = ['laboratory-computer-engineering', 'surveyings', 'testings', 'constructions', 'fluids'];
                @endphp

                @foreach ($categories as $cat)

                @endforeach --}}
                <!-- Computer Engineering -->
                <li class="nav-item {{ request()->routeIs('laboratory-computer-engineering') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('laboratory-computer-engineering.index') ? 'active' : '' }}"
                        href="{{ route('laboratory-computer-engineering.index') }}">
                        <i class="nav-icon fas fa-desktop" style="color: #ffff"></i>
                        <p class="text-light">
                            {{ __('Computer Engineering') }}
                        </p>
                    </a>
                </li>
                <!-- Surveying -->
                <li class="nav-item {{ request()->routeIs('surveyings') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('surveyings.index') ? 'active' : '' }}"
                        href="{{ route('surveyings.index') }}">

                        <i class="nav-icon fas fa-map-marked" style="color: #ffff"> </i>
                        <p class="text-light">
                            {{ __('Surveying') }}
                        </p>
                    </a>
                </li>
                <!-- Testing & Mechanics -->
                <li class="nav-item {{ request()->routeIs('testings') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('testings.index') ? 'active' : '' }}"
                        href="{{ route('testings.index') }}">

                        <i class="nav-icon fas fa-tools" style="color: #ffff"></i>
                        <p class="text-light">
                            Testing & Mechanics
                        </p>
                    </a>
                </li>
                <!-- General Construction -->
                <li class="nav-item {{ request()->routeIs('constructions') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('constructions.index') ? 'active' : '' }}"
                        href="{{ route('constructions.index') }}">
                        <i class="nav-icon fas fa-hard-hat" style="color: #ffff"></i>
                        <p class="text-light">
                            General Construction
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('fluids') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('fluids.index') ? 'active' : '' }}"
                        href="{{ route('fluids.index') }}">
                        <i class="nav-icon fas fa-tint" style="color: #ffff"></i>
                        <p class="text-light">
                            Hydraulics and Fluids
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('laboratory-equipments.index') ? 'active' : '' }}">
                    <a class="nav-link {{ request()->routeIs('laboratory-equipments.index') ? 'active' : '' }}"
                        href="{{ route('laboratory-equipments.index') }}">
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
                            Reports
                        </p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>
