<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Happy Shop') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/sass/dashboard.scss', 'resources/sass/pagination.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Navbar Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>

            <!-- Right-aligned Logout Link -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->username }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Dashboard</h3>
            </div>

            <ul class="list-unstyled components">
              <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                  <a href="{{ route('home') }}">
                      <i class="fas fa-home"></i> Home
                  </a>
              </li>
              <hr />
              @if(Auth::user()->user_type === 'superadmin')
                <li class="{{ request()->routeIs('products') ? 'active' : '' }}">
                    <a href={{ route('products') }}>
                        <i class="fas fa-box"></i> Products
                    </a>
                </li>
                <hr />
              @endif
              <li class="{{ request()->routeIs('transactions*') ? 'active' : '' }}">
                <a href={{ route('transactions.index') }}>
                      <i class="fas fa-money-bill"></i> Transaction
                  </a>
              </li>
              <hr />
              @if(Auth::user()->user_type === 'superadmin')
                <li class="{{ request()->routeIs('report') ? 'active' : '' }}">
                    <a href="{{ route('report') }}">
                        <i class="fas fa-chart-line"></i> Report
                    </a>
                </li>
              @endif
          </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </nav>

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
