<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo.png') }}" alt="navbar brand" class="navbar-brand"
                    height="150" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ Request::is('admin/tickets*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tickets.index') }}">
                        <i class="fas fa-clipboard-check"></i>
                        <p>Tickets Management</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('admin*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#adminDashboard" class="{{ Request::is('admin*') ? '' : 'collapsed' }}" aria-expanded="{{ Request::is('admin*') ? 'true' : 'false' }}">
                        <i class="fas fa-home"></i>
                        <p>Admin</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('admin*') ? 'show' : '' }}" id="adminDashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <span class="sub-item">Main Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.events.index') }}">
                                    <span class="sub-item">Event Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}">
                                    <span class="sub-item">Event Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.banners.index') }}">
                                    <span class="sub-item">Hero Banners</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.index') }}">
                                    <span class="sub-item">Website Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Analytics Section -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#adminAnalytics" class="collapsed" aria-expanded="false">
                        <i class="fas fa-chart-pie"></i>
                        <p>Analytics</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="adminAnalytics">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.analytics.sales') }}">
                                    <span class="sub-item">Sales Analytics</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.transactions') }}">
                                    <span class="sub-item">Transactions</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.event-performance') }}">
                                    <span class="sub-item">Event Performance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.user-stats') }}">
                                    <span class="sub-item">User Stats</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Export Reports Section -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#adminExports" class="collapsed" aria-expanded="false">
                        <i class="fas fa-download"></i>
                        <p>Reports & Exports</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="adminExports">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.export.events') }}">
                                    <span class="sub-item">Export Events</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.orders') }}">
                                    <span class="sub-item">Export Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.sales') }}">
                                    <span class="sub-item">Sales Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.event-performance') }}">
                                    <span class="sub-item">Performance Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#base">
                        <i class="fas fa-layer-group"></i>
                        <p>User</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.users.admins') }}">
                                    <span class="sub-item">Admin</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.organizers') }}">
                                    <span class="sub-item">Organizer</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.users') }}">
                                    <span class="sub-item">User</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#events">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Events</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="events">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.events.index') }}">
                                    <span class="sub-item">All Events</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.events.create') }}">
                                    <span class="sub-item">Add Event</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <p>All Orders</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->