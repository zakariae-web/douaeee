@extends('layouts.app')
@section('header')
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/images/favicon/site.webmanifest" />
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin/dashboard.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('title', 'Tableau de bord enseignant')

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">

<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold mb-2">Tableau de Bord</h1>
                    <p class="text-muted lead">Suivi des progrès des étudiants</p>
                </div>
                <div class="d-flex gap-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3 class="h2 mb-2">{{ $users->total() }}</h3>
                            <p class="mb-0">Étudiants</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="searchStudent" placeholder="Rechercher un étudiant...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Étudiant
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    Email
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-trophy text-primary me-2"></i>
                                    Niveau
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-bar text-primary me-2"></i>
                                    XP
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-microphone text-primary me-2"></i>
                                    Tentatives
                                </div>
                            </th>
                            <th class="border-0 px-4 py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-muted">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="level-badge bg-soft-primary text-primary rounded-pill px-3 py-1">
                                            <i class="fas fa-trophy me-2"></i>
                                            Niveau {{ $user->level }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="xp-badge bg-soft-success text-success rounded-pill px-3 py-1">
                                            <i class="fas fa-star me-2"></i>
                                            {{ $user->xp }} XP
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-soft-primary text-primary rounded-pill">
                                        {{ $user->pronunciation_attempts_count }} tentatives
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <a href="{{ route('admin.user.attempts', $user->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Voir les détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <p>Aucun étudiant trouvé.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <div class="paginate">
            <button class="prevBtn">
                <i class="fas fa-chevron-left me-2"></i>
                <span class="prevBtn-text">Précédent</span>
            </button>
            <div class="containerBtns">
                <div class="leftContainer"></div>
                <button class="activeBtn"></button>
                <div class="rightContainer"></div>
            </div>
            <button class="nextBtn">
                <span class="nextBtn-text">Suivant</span>
                <i class="fas fa-chevron-right ms-2"></i>
            </button>
        </div>
        <div class="paginate-details text-center mt-3 text-muted">
            Page <span id="current-page" class="fw-bold text-primary"></span> sur <span class="fw-bold">{{ $users->lastPage() }}</span> — Total : <span class="fw-bold">{{ $users->total() }}</span> étudiants
        </div>
    </div>
</div>



<script>
    const countPage = {{ $users->lastPage() }};
    const currentPage = {{ $users->currentPage() }};
</script>

<script src="{{ asset('js/pagination.js') }}"></script>
@endsection
