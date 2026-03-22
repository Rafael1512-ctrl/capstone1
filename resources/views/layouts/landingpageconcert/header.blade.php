<header role="banner" style="position: sticky; top: 0; z-index: 1050; width: 100%;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #0a0a0a; border-bottom: 1px solid rgba(220,20,60,0.2); box-shadow: 0 2px 20px rgba(0,0,0,0.5); padding: 12px 0;">
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Categories</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown04">
                            <a class="dropdown-item" href="#">Pop</a>
                            <a class="dropdown-item" href="#">Rock</a>
                            <a class="dropdown-item" href="#">Indie</a>
                            <a class="dropdown-item" href="#">Jazz</a>
                        </div>
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
                                <a class="dropdown-item" href="{{ route('tickets.index') }}"
                                    style="color: rgba(255,255,255,0.75); font-size:0.88rem; padding: 10px 18px;">
                                    <i class="fa fa-ticket" style="width:18px; color:#dc143c;"></i> My Tickets
                                </a>
                                <div style="border-top: 1px solid rgba(255,255,255,0.08); margin: 4px 0;"></div>
                                <a class="dropdown-item" href="#"
                                    style="color: #ff6b6b; font-size:0.88rem; padding: 10px 18px;"
                                    onclick="event.preventDefault(); document.getElementById('logout-confirm-modal').style.display='flex';">
                                    <i class="fa fa-sign-out" style="width:18px;"></i> Logout
                                </a>
                            </div>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <!-- Logout Confirmation Modal -->
                        <div id="logout-confirm-modal"
                            style="display:none; position:fixed; inset:0; z-index:9999;
                                   background: rgba(0,0,0,0.78); backdrop-filter: blur(6px);
                                   align-items:center; justify-content:center;">
                            <div style="background: #111; border: 1px solid rgba(220,20,60,0.25);
                                        border-radius: 18px; padding: 42px 40px; max-width: 380px; width: 90%;
                                        box-shadow: 0 25px 60px rgba(0,0,0,0.7);
                                        text-align: center; animation: popIn 0.25s ease-out;">
                                <div style="width:64px; height:64px; border-radius:50%;
                                            background: rgba(220,20,60,0.12); border: 1.5px solid rgba(220,20,60,0.3);
                                            display:flex; align-items:center; justify-content:center;
                                            margin: 0 auto 22px; font-size:1.6rem; color:#dc143c;">
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
                                               border: 1.5px solid rgba(255,255,255,0.15);
                                               border-radius:10px; color:rgba(255,255,255,0.55);
                                               font-size:0.88rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
                                        onmouseover="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.color='#fff';"
                                        onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(255,255,255,0.55)';">
                                        Stay Here
                                    </button>
                                    <button onclick="document.getElementById('logout-form').submit()"
                                        style="flex:1; padding:11px;
                                               background: linear-gradient(135deg, #dc143c 0%, #8b0000 100%);
                                               border: none; border-radius:10px; color:#fff;
                                               font-size:0.88rem; font-weight:700; cursor:pointer;
                                               box-shadow: 0 4px 15px rgba(220,20,60,0.35); transition:all 0.2s;"
                                        onmouseover="this.style.boxShadow='0 6px 22px rgba(220,20,60,0.6)';"
                                        onmouseout="this.style.boxShadow='0 4px 15px rgba(220,20,60,0.35)';">
                                        Yes, Log Out
                                    </button>
                                </div>
                            </div>
                        </div>
                        <style>
                            @keyframes popIn {
                                from { transform: scale(0.88); opacity: 0; }
                                to   { transform: scale(1);    opacity: 1; }
                            }
                        </style>
                    @endguest
                </div>

                <div class="navbar-nav ml-auto">
                    <form method="post" class="search-form">
                        <span class="icon ion ion-search"></span>
                        <input type="text" class="form-control" placeholder="Search concerts...">
                    </form>
                </div>

            </div>
        </div>
    </nav>
</header>
