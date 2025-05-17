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
    <link rel="stylesheet" href="/css/jeu/results.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('title', 'Vos Résultats')

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section avec animation -->
            <div class="text-center mb-5 results-header">
                <h2 class="display-5 mb-3">Historique des Tentatives</h2>
                <p class="text-muted lead">Suivez votre progression et vos performances</p>
            </div>

            @if($attempts->isEmpty())
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                    </div>
                    <h3 class="h4 mb-3">Aucune tentative pour le moment</h3>
                    <p class="text-muted mb-4">Commencez à jouer pour voir apparaître vos résultats ici !</p>
                    <a href="{{ route('jeu') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-gamepad me-2"></i>
                        Commencer à jouer
                    </a>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="row mb-5">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="stats-icon mb-3 text-primary">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h3 class="h2 mb-2">{{ $attempts->where('success', true)->count() }}</h3>
                                <p class="text-muted mb-0">Réussites</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="stats-icon mb-3 text-danger">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                                <h3 class="h2 mb-2">{{ $attempts->where('success', false)->where('skipped', false)->count() }}</h3>
                                <p class="text-muted mb-0">Erreurs</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="stats-icon mb-3 text-warning">
                                    <i class="fas fa-forward fa-2x"></i>
                                </div>
                                <h3 class="h2 mb-2">{{ $attempts->where('skipped', true)->count() }}</h3>
                                <p class="text-muted mb-0">Sauts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table des résultats -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Lettre Cible</th>
                                        <th class="px-4 py-3">Lettre Prononcée</th>
                                        <th class="px-4 py-3">Résultat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attempts as $attempt)
                                        <tr class="result-row">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                                    {{ $attempt->created_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="letter-badge">{{ $attempt->letter }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $attempt->attempted_word ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($attempt->success)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                        <i class="fas fa-check me-1"></i> Correct
                                                    </span>
                                                @elseif($attempt->skipped)
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                                                        <i class="fas fa-forward me-1"></i> Sauté
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                                        <i class="fas fa-times me-1"></i> Incorrect
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination personnalisée -->
                <div class="mt-4">
                    <div class="paginate">
                        <button class="prevBtn">
                            <span class="prevBtn-icon">←</span>
                            <span class="prevBtn-text">Précédent</span>
                        </button>
                        <div class="containerBtns">
                            <div class="leftContainer"></div>
                            <button class="activeBtn"></button>
                            <div class="rightContainer"></div>
                        </div>
                        <button class="nextBtn">
                            <span class="nextBtn-text">Suivant</span>
                            <span class="nextBtn-icon">→</span>
                        </button>
                    </div>
                    <div class="paginate-details text-center mt-3">
                        <span class="text-muted">
                            Page <strong id="current-page"></strong> sur {{ $attempts->lastPage() }} — 
                            <strong>{{ $attempts->total() }}</strong> tentatives au total
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<script>
    const countPage = {{ $attempts->lastPage() }};
    const currentPage = {{ $attempts->currentPage() }};
</script>
<script src="{{ asset('js/pagination.js') }}"></script>
@endsection
