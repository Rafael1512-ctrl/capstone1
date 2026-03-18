<header role="banner">
    <nav class="navbar navbar-expand-lg  bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand " href="{{ route('landingconcert') }}">LuxTix</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample05"
          aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

   
        <div class="collapse navbar-collapse" id="navbarsExample05">
          <ul class="navbar-nav pl-md-5 ml-auto">
            <li class="nav-item">
              <a class="nav-link active" href="{{ route('landingconcert') }}">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Discover</a>
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
              <a class="nav-link" href="#">My Tickets</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
            </li>
          </ul>

          <div class="navbar-nav ml-auto align-items-center">
            @guest
                <li class="nav-item mr-2">
                    <a class="nav-link btn btn-outline-primary px-3 py-1" href="{{ route('login') }}" style="border-radius: 20px; border-color: #9d50bb; color: #9d50bb;">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary px-3 py-1 text-white" href="{{ route('register') }}" style="border-radius: 20px; background: linear-gradient(to right, #9d50bb, #6e48aa); border: none;">Register</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }} ({{ Auth::user()->role }})
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        @if(Auth::user()->role === 'Admin')
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @endif
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
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