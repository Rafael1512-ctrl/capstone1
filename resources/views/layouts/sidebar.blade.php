<!-- Sidebar — Dark Concert Theme -->
<div class="sidebar" data-background-color="dark" id="tixly-sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <div class="logo-header py-3 px-4"
            style="background: linear-gradient(135deg,#0d0d0d,#1a0508); border-bottom: 1px solid rgba(220,20,60,0.2);">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center gap-2 text-decoration-none">
                <span
                    style="font-family:'DM Serif Display',serif; font-size:22px; font-weight:800; color:#dc143c; letter-spacing:-0.5px;">TIXLY</span>
                <span
                    style="font-size:9px; background:rgba(220,20,60,0.15); color:#dc143c; border:1px solid rgba(220,20,60,0.3); border-radius:4px; padding:2px 6px; font-weight:700; letter-spacing:1.5px;">ADMIN</span>
            </a>
        </div>
    </div>

    <!-- User badge -->
    <div class="px-3 py-3" style="border-bottom:1px solid rgba(255,255,255,0.05);">
        <div class="d-flex align-items-center gap-3">
            @php $sidebarUser = Auth::user(); @endphp
            @if($sidebarUser->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($sidebarUser->profile_photo))
                <img src="{{ asset('storage/' . $sidebarUser->profile_photo) }}"
                     alt="Avatar"
                     style="width:38px;height:38px;border-radius:10px;object-fit:cover;flex-shrink:0;border:2px solid rgba(220,20,60,0.4);">
            @else
                <div
                    style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#dc143c,#8b0000);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:15px;flex-shrink:0;">
                    {{ strtoupper(substr($sidebarUser->name, 0, 1)) }}
                </div>
            @endif
            <div style="overflow:hidden;">
                <div
                    style="color:#fff;font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ Auth::user()->name }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,0.35);letter-spacing:1px;text-transform:uppercase;">
                    {{ Auth::user()->isAdmin() ? 'Administrator' : 'Organizer' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Items -->
    <div class="sidebar-wrapper" style="overflow-y:auto;height:calc(100vh - 130px);padding:12px 0;">
        <ul class="nav flex-column px-2" style="list-style:none;gap:2px;">

            <!-- Dashboard -->
            <li><a href="{{ route('admin.dashboard') }}"
                    class="tixly-nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a></li>

            <!-- My Profile -->
            <li><a href="{{ route('admin.profile.show') }}"
                    class="tixly-nav-link {{ Request::is('admin/profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i><span>My Profile</span>
                </a></li>

            <!-- Divider -->
            <li>
                <div class="tixly-nav-divider">Events</div>
            </li>

            <!-- Events -->
            <li>
                <a href="#eventMenu" data-bs-toggle="collapse"
                    class="tixly-nav-link {{ Request::is('admin/events*', 'admin/categories*', 'admin/capacity-requests*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i><span>Event Management</span>
                    <i class="fas fa-chevron-down ms-auto tixly-caret"></i>
                </a>
                <div class="collapse {{ Request::is('admin/events*', 'admin/categories*', 'admin/capacity-requests*') ? 'show' : '' }}"
                    id="eventMenu">
                    <ul class="tixly-sub-nav">
                        <li><a href="{{ route('admin.events.index') }}">All Events</a></li>
                        <li><a href="{{ route('admin.events.create') }}">Add New Event</a></li>
                        <li><a href="{{ route('admin.categories.index') }}">Event Categories</a></li>
                        <li><a href="{{ route('admin.capacity.index') }}">Capacity Requests</a></li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <li>
                <div class="tixly-nav-divider">Sales</div>
            </li>

            <!-- Tickets & Sales -->
            <li>
                <a href="#salesMenu" data-bs-toggle="collapse"
                    class="tixly-nav-link {{ Request::is('admin/tickets*', 'admin/orders*', 'admin/export*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i><span>Tickets & Sales</span>
                    <i class="fas fa-chevron-down ms-auto tixly-caret"></i>
                </a>
                <div class="collapse {{ Request::is('admin/tickets*', 'admin/orders*', 'admin/export*') ? 'show' : '' }}"
                    id="salesMenu">
                    <ul class="tixly-sub-nav">
                        <li><a href="{{ route('admin.tickets.index') }}">Ticket Management</a></li>
                        <li><a href="{{ route('admin.orders.index') }}">Order History</a></li>
                        <li><a href="{{ route('admin.export.sales') }}">Export Report</a></li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <li>
                <div class="tixly-nav-divider">Users</div>
            </li>

            <!-- Users -->
            <li>
                <a href="#userMenu" data-bs-toggle="collapse"
                    class="tixly-nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i><span>User Management</span>
                    <i class="fas fa-chevron-down ms-auto tixly-caret"></i>
                </a>
                <div class="collapse {{ Request::is('admin/users*') ? 'show' : '' }}" id="userMenu">
                    <ul class="tixly-sub-nav">
                        <li><a href="{{ route('admin.users.admins') }}">Admins</a></li>
                        <li><a href="{{ route('admin.users.organizers') }}">Organizers</a></li>
                        <li><a href="{{ route('admin.users.users') }}">Users</a></li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <li>
                <div class="tixly-nav-divider">System</div>
            </li>

            <!-- Analytics -->
            <li>
                <a href="#analyticsMenu" data-bs-toggle="collapse"
                    class="tixly-nav-link {{ Request::is('admin/analytics*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i><span>Analytics</span>
                    <i class="fas fa-chevron-down ms-auto tixly-caret"></i>
                </a>
                <div class="collapse {{ Request::is('admin/analytics*') ? 'show' : '' }}" id="analyticsMenu">
                    <ul class="tixly-sub-nav">
                        <li><a href="{{ route('admin.analytics.sales') }}">Sales Stats</a></li>
                        <li><a href="{{ route('admin.analytics.transactions') }}">Transaction Logs</a></li>
                        <li><a href="{{ route('admin.analytics.event-performance') }}">Event Performance</a></li>
                    </ul>
                </div>
            </li>

            <!-- Settings -->
            <li>
                <a href="#settingsMenu" data-bs-toggle="collapse"
                    class="tixly-nav-link {{ Request::is('admin/banners*', 'admin/settings*') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i><span>Website Settings</span>
                    <i class="fas fa-chevron-down ms-auto tixly-caret"></i>
                </a>
                <div class="collapse {{ Request::is('admin/banners*', 'admin/settings*') ? 'show' : '' }}"
                    id="settingsMenu">
                    <ul class="tixly-sub-nav">
                        <li><a href="{{ route('admin.banners.index') }}">Discover Banners</a></li>
                        <li><a href="{{ route('admin.settings.index') }}">About Us Banners</a></li>
                    </ul>
                </div>
            </li>

            <!-- Logout -->
            <li style="margin-top: 12px;">
                <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" id="sidebar-logout-btn" class="tixly-nav-link w-100 text-start border-0"
                        style="background:rgba(220,20,60,0.08); color:#ff6080; border:1px solid rgba(220,20,60,0.15) !important; border-radius:10px;">
                        <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                    </button>
                </form>
            </li>

        </ul>
    </div>
</div>

<style>
    /* ════════════════════════════════════════
   TIXLY ADMIN — DARK CONCERT SIDEBAR
   ════════════════════════════════════════ */
    #tixly-sidebar {
        background: linear-gradient(180deg, #0d0d0d 0%, #110508 100%) !important;
        border-right: 1px solid rgba(220, 20, 60, 0.12);
        font-family: 'Public Sans', sans-serif;
    }

    /* Nav link */
    .tixly-nav-link {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 10px 14px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.5) !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 2px;
        cursor: pointer;
    }

    .tixly-nav-link i {
        font-size: 14px;
        width: 18px;
        flex-shrink: 0;
    }

    .tixly-nav-link:hover {
        background: rgba(220, 20, 60, 0.08) !important;
        color: #fff !important;
    }

    .tixly-nav-link.active {
        background: linear-gradient(90deg, rgba(220, 20, 60, 0.2), rgba(139, 0, 0, 0.1)) !important;
        color: #fff !important;
        border-left: 3px solid #dc143c;
    }

    .tixly-nav-link.active i {
        color: #dc143c;
    }

    /* Caret */
    .tixly-caret {
        font-size: 10px !important;
        transition: transform 0.3s;
    }

    .tixly-nav-link[aria-expanded="true"] .tixly-caret {
        transform: rotate(180deg);
    }

    /* Sub nav */
    .tixly-sub-nav {
        list-style: none;
        padding: 4px 0 4px 42px;
        margin: 0;
    }

    .tixly-sub-nav li a {
        display: block;
        padding: 7px 12px;
        color: rgba(255, 255, 255, 0.35);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
        border-left: 2px solid transparent;
    }

    .tixly-sub-nav li a:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.04);
        border-left-color: #dc143c;
    }

    /* Divider label */
    .tixly-nav-divider {
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.2);
        padding: 12px 14px 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const logoutBtn = document.getElementById('sidebar-logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function () {
                Swal.fire({
                    html: `
                        <div style="padding:10px 0;">
                            <div style="width:70px;height:70px;border-radius:50%;background:linear-gradient(135deg,rgba(220,20,60,0.15),rgba(139,0,0,0.1));border:2px solid rgba(220,20,60,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px;">🔐</div>
                            <h3 style="color:#fff;font-weight:800;font-size:20px;margin-bottom:8px;font-family:'Inter',sans-serif;">End Session?</h3>
                            <p style="color:rgba(255,255,255,0.4);font-size:14px;margin:0;line-height:1.6;">Yakin ingin logout dari<br><strong style="color:rgba(255,255,255,0.7);">Admin Dashboard TIXLY</strong>?</p>
                        </div>
                    `,
                    background: 'linear-gradient(135deg, #1a0a0f 0%, #110710 100%)',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup:         'tixly-swal-popup',
                        confirmButton: 'btn btn-danger px-4 py-2 mx-1',
                        cancelButton:  'btn btn-secondary px-4 py-2 mx-1',
                        actions:       'tixly-swal-actions',
                    },
                    buttonsStyling: false,
                    showClass:    { popup: 'animate__animated animate__fadeInDown animate__faster' },
                    hideClass:    { popup: 'animate__animated animate__fadeOutUp animate__faster' },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('sidebar-logout-form').submit();
                    }
                });
            });
        }
    });
</script>