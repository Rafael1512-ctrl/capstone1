<style>
    #tixly-header {
        position: sticky;
        top: 0;
        z-index: 1050;
        width: 100%;
        transition: box-shadow 0.35s ease;
    }
    #logout-form {
        display: none !important;
        position: absolute !important;
        width: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        visibility: hidden !important;
    }
    #tixly-navbar {
        background: #000000 !important;
        border-bottom: 1px solid rgba(220,20,60,0.2);
        box-shadow: 0 2px 20px rgba(0,0,0,0.5);
        padding: 12px 0;
        transition: box-shadow 0.35s ease, border-color 0.35s ease;
    }
    #tixly-header.scrolled #tixly-navbar {
        box-shadow: 0 4px 30px rgba(220,20,60,0.18), 0 2px 12px rgba(0,0,0,0.7) !important;
        border-bottom-color: rgba(220,20,60,0.45) !important;
    }
</style>

<header id="tixly-header" role="banner">
    <nav id="tixly-navbar" class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}"
                style="font-family: Georgia, serif; font-weight: 800; font-size: 1.6rem;
                       background: linear-gradient(90deg, #dc143c, #ff6b6b);
                       -webkit-background-clip: text; -webkit-text-fill-color: transparent;
                       background-clip: text; letter-spacing: 2px; text-decoration: none;">TIXLY</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
                aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation"
                style="border-color: rgba(220,20,60,0.4);">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample05">
                <ul class="navbar-nav pl-md-5 ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#concerts">Discover</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#my-tickets">My Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Contact</a>
                    </li>
                </ul>

                <div class="navbar-nav ml-auto align-items-center" style="gap: 10px;">
                    @guest
                        <li class="nav-item" style="list-style:none;">
                            <a href="{{ route('login') }}"
                                style="display: inline-flex; align-items: center; gap: 6px;
                                       padding: 7px 20px;
                                       border: 1.5px solid rgba(220,20,60,0.6);
                                       border-radius: 25px;
                                       color: #ff6b6b;
                                       font-size: 0.87rem;
                                       font-weight: 600;
                                       text-decoration: none;
                                       letter-spacing: 0.5px;
                                       transition: all 0.25s;"
                                onmouseover="this.style.background='rgba(220,20,60,0.12)'; this.style.borderColor='#dc143c'; this.style.color='#fff';"
                                onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(220,20,60,0.6)'; this.style.color='#ff6b6b';">
                                LOGIN
                            </a>
                        </li>
                        <li class="nav-item" style="list-style:none;">
                            <a href="{{ route('register') }}"
                                style="display: inline-flex; align-items: center; gap: 6px;
                                       padding: 7px 22px;
                                       border-radius: 25px;
                                       color: #fff;
                                       font-size: 0.87rem;
                                       font-weight: 700;
                                       text-decoration: none;
                                       background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
                                       border: 1.5px solid transparent;
                                       letter-spacing: 0.5px;
                                       box-shadow: 0 4px 15px rgba(220,20,60,0.35);
                                       transition: all 0.25s;"
                                onmouseover="this.style.boxShadow='0 6px 22px rgba(220,20,60,0.55)'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.35)'; this.style.transform='translateY(0)';">
                                REGISTER
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown" style="list-style:none;">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"
                                style="display: inline-flex; align-items: center; gap: 8px;
                                       padding: 6px 18px;
                                       border: 1.5px solid rgba(220,20,60,0.4);
                                       border-radius: 25px;
                                       color: #fff;
                                       font-weight: 600;
                                       font-size: 0.88rem;">
                                <i class="fa fa-user-circle"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown"
                                style="background: #1a1a1a; border: 1px solid rgba(220,20,60,0.2);
                                       border-radius: 10px; min-width: 180px;">
                                @if (Auth::user()->role === 'Admin')
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}"
                                        style="color: rgba(255,255,255,0.75); font-size:0.88rem; padding: 10px 18px;">
                                        <i class="fa fa-tachometer" style="width:18px; color:#dc143c;"></i> Dashboard
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('profile.show') }}"
                                    style="color: rgba(255,255,255,0.75); font-size:0.88rem; padding: 10px 18px;">
                                    <i class="fa fa-user" style="width:18px; color:#dc143c;"></i> My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('tickets.index') }}"
                                    style="color: rgba(255,255,255,0.75); font-size:0.88rem; padding: 10px 18px;">
                                    <i class="fa fa-ticket" style="width:18px; color:#dc143c;"></i> My Tickets
                                </a>
                                
                                <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 4px 0;"></div>
                                <a class="dropdown-item" href="#logout"
                                    style="color: #ff6b6b; font-size:0.88rem; padding: 10px 18px;"
                                    onclick="event.preventDefault(); document.getElementById('logout-confirm-modal').style.display='flex';">
                                    <i class="fa fa-sign-out" style="width:18px;"></i> Logout
                                </a>
                            </div>
                        </li>

                    @endguest
                </div>  

            </div>
        </div>
    </nav>
</header>

@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" aria-hidden="true" style="display:none !important; position:fixed; top:-9999px; left:-9999px; width:0; height:0; overflow:hidden; visibility:hidden;">
    @csrf
</form>

<!-- Logout Confirmation Modal — di luar header agar position:fixed bekerja benar -->
<div id="logout-confirm-modal"
    style="display:none; position:fixed; top:0; left:0%; right:0; bottom:0; width:100%; height:100%; z-index:99999;
           background:rgba(0,0,0,0.82); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);
           align-items:center; justify-content:center;">
    <div id="logout" style="background:#111;top:-43%; border:1px solid rgba(220,20,60,0.3);
                border-radius:18px; padding:42px 40px; max-width:380px; width:90%;
                box-shadow:0 25px 60px rgba(0,0,0,0.8);
                text-align:center; animation:popIn 0.25s ease-out;
                position:relative; margin:auto;">
        <div style="width:64px; height:64px; border-radius:50%;
                    background:rgba(220,20,60,0.12); border:1.5px solid rgba(220,20,60,0.3);
                    display:flex; align-items:center; justify-content:center;
                    margin:0 auto 22px; font-size:1.6rem; color:#dc143c;">
            <i class="fa fa-sign-out"></i>
        </div>
        <h3 style="color:#fff; font-size:1.35rem; font-weight:700; margin-bottom:10px; font-family:Georgia,serif;">
            Ready to Leave?
        </h3>
        <p style="color:rgba(255,255,255,0.45); font-size:0.9rem; line-height:1.6; margin-bottom:30px;">
            You'll need to log in again to access your tickets and events.
        </p>
        <div style="display:flex; gap:12px; justify-content:center;">
            <button onclick="document.getElementById('logout-confirm-modal').style.display='none'"
                style="flex:1; padding:11px; background:transparent;
                       border:1.5px solid rgba(255,255,255,0.15); border-radius:10px;
                       color:rgba(255,255,255,0.55); font-size:0.88rem; font-weight:600;
                       cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.color='#fff';"
                onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(255,255,255,0.55)';">
                Stay Here
            </button>
            <button onclick="document.getElementById('logout-form').submit()"
                style="flex:1; padding:11px;
                       background:linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
                       border:none; border-radius:10px; color:#fff;
                       font-size:0.88rem; font-weight:700; cursor:pointer;
                       box-shadow:0 4px 15px rgba(220,20,60,0.35); transition:all 0.2s;"
                onmouseover="this.style.boxShadow='0 6px 22px rgba(220,20,60,0.6)';"
                onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.35)';">
                Yes, Log Out
            </button>
        </div>
    </div>
</div>
@endauth

<style>
    @keyframes popIn {
        from { transform:scale(0.88); opacity:0; }
        to   { transform:scale(1);    opacity:1; }
    }
    #logout-confirm-modal {
        display: none;
    }
    #logout-confirm-modal.active {
        display: flex !important;
    }
</style>

<script>
    (function() {
        /* ── 1. Sticky header shadow on scroll ── */
        var header = document.getElementById('tixly-header');
        if (header) {
            window.addEventListener('scroll', function() {
                header.classList.toggle('scrolled', window.scrollY > 10);
            }, { passive: true });
        }

        /* ── 2. Smooth scroll for anchor links (#xxx) ── */
        document.addEventListener('DOMContentLoaded', function() {
            var HEADER_HEIGHT = header ? header.offsetHeight + 8 : 70;

            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    var targetId = this.getAttribute('href').slice(1);
                    if (!targetId) return; // href="#" only
                    var target = document.getElementById(targetId);
                    if (!target) return;
                    e.preventDefault();

                    // Close mobile navbar if open
                    var navCollapse = document.getElementById('navbarsExample05');
                    if (navCollapse && navCollapse.classList.contains('show')) {
                        navCollapse.classList.remove('show');
                    }

                    var targetTop = target.getBoundingClientRect().top + window.scrollY - HEADER_HEIGHT;
                    window.scrollTo({ top: targetTop, behavior: 'smooth' });
                });
            });

            /* ── 3. Page-transition overlay for real URL links ── */
            var overlay = document.createElement('div');
            overlay.id = 'page-transition-overlay';
            overlay.style.cssText = [
                'position: fixed',
                'inset: 0',
                'z-index: 99999',
                'background: #0d0d0d',
                'opacity: 0',
                'pointer-events: none',
                'transition: opacity 0.35s ease'
            ].join(';');
            document.body.appendChild(overlay);

            // Fade in on page load
            requestAnimationFrame(function() {
                overlay.style.opacity = '0';
            });

            // Intercept navigation links (not anchors, not new tabs)
            document.querySelectorAll('a:not([href^="#"]):not([target="_blank"])').forEach(function(link) {
                var href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript') || href.startsWith('mailto')) return;

                link.addEventListener('click', function(e) {
                    var destination = this.href;
                    if (!destination || window.location.href === destination) return;
                    e.preventDefault();
                    overlay.style.pointerEvents = 'all';
                    overlay.style.opacity = '1';
                    setTimeout(function() {
                        window.location.href = destination;
                    }, 350);
                });
            });

            // Fade out when page becomes visible (back/forward)
            window.addEventListener('pageshow', function() {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            });
        });
    })();
</script>
