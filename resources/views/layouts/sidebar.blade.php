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
                <li class="nav-item active">
                    <a data-bs-toggle="collapse" href="#adminDashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Admin Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="adminDashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <span class="sub-item">📊 Main Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.events.index') }}">
                                    <span class="sub-item">🎪 Event Management</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.index') }}">
                                    <span class="sub-item">🏷️ Event Categories</span>
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
                                    <span class="sub-item">📈 Sales Analytics</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.transactions') }}">
                                    <span class="sub-item">💳 Transactions</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.event-performance') }}">
                                    <span class="sub-item">⭐ Event Performance</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.user-stats') }}">
                                    <span class="sub-item">👥 User Stats</span>
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
                                    <span class="sub-item">📥 Export Events</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.orders') }}">
                                    <span class="sub-item">📥 Export Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.sales') }}">
                                    <span class="sub-item">📥 Sales Report</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.export.event-performance') }}">
                                    <span class="sub-item">📥 Performance Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="dashboard">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="../demo1/index.html">
                                    <span class="sub-item">Dashboard 1</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
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
                            <li>
                                <a href="components/panels.html">
                                    <span class="sub-item">Panels</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/notifications.html">
                                    <span class="sub-item">Notifications</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/sweetalert.html">
                                    <span class="sub-item">Sweet Alert</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/font-awesome-icons.html">
                                    <span class="sub-item">Font Awesome Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/simple-line-icons.html">
                                    <span class="sub-item">Simple Line Icons</span>
                                </a>
                            </li>
                            <li>
                                <a href="components/typography.html">
                                    <span class="sub-item">Typography</span>
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
                                <a href="{{ route('events.index') }}">
                                    <span class="sub-item">All Events</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('events.create') }}">
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
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarLayouts">
                        <i class="fas fa-th-list"></i>
                        <p>Sidebar Layouts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sidebarLayouts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="sidebar-style-2.html">
                                    <span class="sub-item">Sidebar Style 2</span>
                                </a>
                            </li>
                            <li>
                                <a href="icon-menu.html">
                                    <span class="sub-item">Icon Menu</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#forms">
                        <i class="fas fa-pen-square"></i>
                        <p>Forms</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="forms">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="forms/forms.html">
                                    <span class="sub-item">Basic Form</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#tables">
                        <i class="fas fa-table"></i>
                        <p>Tables</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="tables">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="tables/tables.html">
                                    <span class="sub-item">Basic Table</span>
                                </a>
                            </li>
                            <li>
                                <a href="tables/datatables.html">
                                    <span class="sub-item">Datatables</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#maps">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Maps</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="maps">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="maps/googlemaps.html">
                                    <span class="sub-item">Google Maps</span>
                                </a>
                            </li>
                            <li>
                                <a href="maps/jsvectormap.html">
                                    <span class="sub-item">Jsvectormap</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#charts">
                        <i class="far fa-chart-bar"></i>
                        <p>Charts</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="charts">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="charts/charts.html">
                                    <span class="sub-item">Chart Js</span>
                                </a>
                            </li>
                            <li>
                                <a href="charts/sparkline.html">
                                    <span class="sub-item">Sparkline</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="widgets.html">
                        <i class="fas fa-desktop"></i>
                        <p>Widgets</p>
                        <span class="badge badge-success">4</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../documentation/index.html">
                        <i class="fas fa-file"></i>
                        <p>Documentation</p>
                        <span class="badge badge-secondary">1</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#submenu">
                        <i class="fas fa-bars"></i>
                        <p>Menu Levels</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="submenu">
                        <ul class="nav nav-collapse">
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav1">
                                    <span class="sub-item">Level 1</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav1">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a data-bs-toggle="collapse" href="#subnav2">
                                    <span class="sub-item">Level 1</span>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="subnav2">
                                    <ul class="nav nav-collapse subnav">
                                        <li>
                                            <a href="#">
                                                <span class="sub-item">Level 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sub-item">Level 1</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
