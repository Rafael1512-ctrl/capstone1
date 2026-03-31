<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo.png') }}" alt="navbar brand" class="navbar-brand" height="150" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                
                {{-- 1. OVERVIEW --}}
                <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin/profile*') ? 'active' : '' }}">
                    <a href="{{ route('admin.profile.show') }}">
                        <i class="fas fa-user-circle"></i>
                        <p>My Profile</p>
                    </a>
                </li>

                {{-- 2. EVENT MANAGEMENT --}}
                <li class="nav-item {{ Request::is('admin/events*', 'admin/categories*', 'admin/capacity-requests*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#eventMenu">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Events Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin/events*', 'admin/categories*', 'admin/capacity-requests*') ? 'show' : '' }}" id="eventMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('admin.events.index') }}"><span class="sub-item">All Events</span></a></li>
                            <li><a href="{{ route('admin.events.create') }}"><span class="sub-item">Add New Event</span></a></li>
                            <li><a href="{{ route('admin.categories.index') }}"><span class="sub-item">Event Categories</span></a></li>
                            <li><a href="{{ route('admin.capacity.index') }}"><span class="sub-item">Capacity Requests</span></a></li>
                        </ul>
                    </div>
                </li>

                {{-- 3. TICKETS & SALES --}}
                <li class="nav-item {{ Request::is('admin/tickets*', 'admin/orders*', 'admin/export*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#salesMenu">
                        <i class="fas fa-shopping-cart"></i>
                        <p>Tickets & Sales</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin/tickets*', 'admin/orders*', 'admin/export*') ? 'show' : '' }}" id="salesMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('admin.tickets.index') }}"><span class="sub-item">Ticket Management</span></a></li>
                            <li><a href="{{ route('admin.orders.index') }}"><span class="sub-item">All Order History</span></a></li>
                            <li><a href="{{ route('admin.export.sales') }}"><span class="sub-item">Export Sales Report</span></a></li>
                        </ul>
                    </div>
                </li>

                {{-- 4. USER HUB --}}
                <li class="nav-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#userMenu">
                        <i class="fas fa-users"></i>
                        <p>User Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin/users*') ? 'show' : '' }}" id="userMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('admin.users.admins') }}"><span class="sub-item">Admin</span></a></li>
                            <li><a href="{{ route('admin.users.organizers') }}"><span class="sub-item">Organizer</span></a></li>
                            <li><a href="{{ route('admin.users.users') }}"><span class="sub-item">User</span></a></li>
                        </ul>
                    </div>
                </li>

                {{-- 5. ANALYTICS --}}
                <li class="nav-item {{ Request::is('admin/analytics*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#analyticsMenu">
                        <i class="fas fa-chart-line"></i>
                        <p>Analytics</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin/analytics*') ? 'show' : '' }}" id="analyticsMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('admin.analytics.sales') }}"><span class="sub-item">Sales Stats</span></a></li>
                            <li><a href="{{ route('admin.analytics.transactions') }}"><span class="sub-item">Transaction Logs</span></a></li>
                            <li><a href="{{ route('admin.analytics.event-performance') }}"><span class="sub-item">Event Performance</span></a></li>
                        </ul>
                    </div>
                </li>

                {{-- 6. SYSTEM SETTINGS --}}
                <li class="nav-item {{ Request::is('admin/banners*', 'admin/settings*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#settingsMenu">
                        <i class="fas fa-cogs"></i>
                        <p>Website Settings</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin/banners*', 'admin/settings*') ? 'show' : '' }}" id="settingsMenu">
                        <ul class="nav nav-collapse">
                            <li><a href="{{ route('admin.banners.index') }}"><span class="sub-item">Discover Banners</span></a></li>
                            <li><a href="{{ route('admin.settings.index') }}"><span class="sub-item">About Us Banners</span></a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>