@yield('header')
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container">
                <a href="{{('/')}}" class="navbar-brand">
                    <img src="{{ asset('images/brandname.png') }}" alt="Logo" class="brandname">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Center Navigation -->
                    <ul class="navbar-nav mx-auto">
                        @auth
                            <li class="nav-item {{ Request::routeIs('jeu') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('jeu') }}">
                                    <i class="fas fa-gamepad me-1"></i>
                                    Jouer
                                </a>
                            </li>
                            @if(Auth::user()->role === 'teacher')
                                <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.letters.index') }}">
                                        <i class="fas fa-spell-check me-1"></i> Gestion des Lettres
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto auth-buttons">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link login-btn" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link register-btn" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i>
                                        {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown user-dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('results') }}">
                                        <i class="fas fa-history me-2"></i>
                                        Mes tentatives
                                    </a>
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        <i class="fas fa-home me-2"></i>
                                        Home
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="">
            @yield('content')
        </main>
    </div>
    <script type="module" src="{{ asset('js/ar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="{{ asset('js/speech.js') }}"  type="module"></script>
    <script src="{{ asset('js/game/index.js') }}"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
</body>
</html>
