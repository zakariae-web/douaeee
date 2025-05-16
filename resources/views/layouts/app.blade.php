<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/jeu/index.css">
    <link rel="stylesheet" href="/css/auth.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar {
            padding: 1rem 0;
            background: white !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1) !important;
        }

        .navbar-brand img {
            height: 40px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .nav-link {
            position: relative;
            padding: 0.5rem 1.2rem !important;
            font-weight: 600;
            color: #2c3e50 !important;
            transition: all 0.3s ease;
            margin: 0 0.3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link i {
            margin-right: 0.5rem;
        }

        .nav-link:hover {
            color: #3490dc !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #3490dc;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 70%;
        }

        .nav-item.active .nav-link {
            color: #3490dc !important;
        }

        .nav-item.active .nav-link::after {
            width: 70%;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
        }

        .navbar-collapse {
            justify-content: center;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .auth-buttons .nav-link {
            border-radius: 20px;
            padding: 0.5rem 1.5rem !important;
            min-width: 120px;
            text-align: center;
            justify-content: center;
        }

        .auth-buttons .login-btn {
            color: #3490dc !important;
            border: 2px solid #3490dc;
        }

        .auth-buttons .register-btn {
            background: linear-gradient(45deg, #3490dc, #4c669f);
            color: white !important;
        }

        .auth-buttons .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 144, 220, 0.2);
        }

        .user-dropdown {
            margin-left: 1rem;
        }

        .user-dropdown .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .user-dropdown .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            border-radius: 15px;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #3490dc;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                align-items: flex-start;
                padding: 1rem 0;
            }

            .auth-buttons {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }

            .auth-buttons .nav-link {
                width: 100%;
                margin: 0;
            }

            .user-dropdown {
                margin-left: 0;
                width: 100%;
            }

            .user-dropdown .nav-link {
                justify-content: flex-start;
            }
        }
    </style>
</head>
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
</body>
</html>
