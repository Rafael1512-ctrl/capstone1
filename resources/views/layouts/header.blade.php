<!-- Header — Dark Concert Theme -->
<div class="main-header" id="tixly-header">
    <nav class="navbar navbar-expand-lg px-4 d-flex align-items-center justify-content-between" style="height:64px;">

        <!-- Left: Page title breadcrumb -->
        <div class="d-flex align-items-center gap-3">
            <div style="font-size:11px; color:rgba(255,255,255,0.3); letter-spacing:1.5px; text-transform:uppercase;">
                TIXLY <span style="color:rgba(220,20,60,0.6); margin: 0 6px;">›</span>
                <span style="color:rgba(255,255,255,0.55);">Admin Panel</span>
            </div>
        </div>

        <!-- Right: Actions -->
        <ul class="navbar-nav ms-auto d-flex flex-row align-items-center" style="gap:8px; list-style:none; margin:0; padding:0;">

            <!-- Live time -->
            <li class="d-none d-lg-flex align-items-center" style="margin-right:8px;">
                <span id="tixly-clock" style="font-size:12px; color:rgba(255,255,255,0.3); font-weight:600; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07); border-radius:8px; padding:5px 12px;"></span>
            </li>

            <!-- User Dropdown -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center gap-2 text-decoration-none"
                   data-bs-toggle="dropdown" aria-expanded="false"
                   style="padding:6px 14px; border-radius:10px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
                    <div style="width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,#dc143c,#8b0000);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:13px;flex-shrink:0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span style="color:#fff; font-weight:700; font-size:13px;">{{ explode(' ', Auth::user()->name)[0] }}</span>
                    <i class="fas fa-chevron-down" style="font-size:10px; color:rgba(255,255,255,0.4);"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="background:#1a0a0a; border:1px solid rgba(220,20,60,0.2); border-radius:14px; padding:8px; min-width:220px; margin-top:8px; box-shadow:0 20px 60px rgba(0,0,0,0.6);">
                    <li>
                        <div class="px-3 py-2 mb-1" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            <div style="color:#fff; font-weight:700; font-size:14px;">{{ Auth::user()->name }}</div>
                            <div style="color:rgba(255,255,255,0.4); font-size:11px;">{{ Auth::user()->email }}</div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.profile.show') }}"
                           style="color:rgba(255,255,255,0.7); border-radius:8px; padding:9px 12px; font-size:13px; font-weight:600; transition:all 0.2s;">
                            <i class="fas fa-user-circle" style="color:#dc143c; width:16px;"></i> My Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('landing') }}" target="_blank"
                           style="color:rgba(255,255,255,0.7); border-radius:8px; padding:9px 12px; font-size:13px; font-weight:600; transition:all 0.2s;">
                            <i class="fas fa-external-link-alt" style="color:#dc143c; width:16px;"></i> View Website
                        </a>
                    </li>
                    <li><hr style="border-color:rgba(255,255,255,0.07); margin:6px 0;"></li>
                    <li>
                        <form id="header-logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" id="header-logout-btn"
                                class="dropdown-item d-flex align-items-center gap-2 w-100 border-0"
                                style="color:#ff6080; background:rgba(220,20,60,0.08); border-radius:8px; padding:9px 12px; font-size:13px; font-weight:700; cursor:pointer;">
                                <i class="fas fa-sign-out-alt" style="width:16px;"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>

<style>
/* ════════════════════════════
   TIXLY ADMIN HEADER
   ════════════════════════════ */
#tixly-header {
    background: linear-gradient(90deg, #0d0d0d, #110508) !important;
    border-bottom: 1px solid rgba(220,20,60,0.12) !important;
    box-shadow: 0 2px 20px rgba(0,0,0,0.4) !important;
}
.dropdown-item:hover {
    background: rgba(220,20,60,0.08) !important;
    color: #fff !important;
}
</style>

<script>
// Live clock
(function() {
    function updateClock() {
        const el = document.getElementById('tixly-clock');
        if (!el) return;
        const now = new Date();
        el.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false }) + ' WIB';
    }
    document.addEventListener('DOMContentLoaded', function() {
        updateClock();
        setInterval(updateClock, 1000);

        // Logout confirm
        const logoutBtn = document.getElementById('header-logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
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
                        document.getElementById('header-logout-form').submit();
                    }
                });
            });
        }
    });
})();
</script>
