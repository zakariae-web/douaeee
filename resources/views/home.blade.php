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
    <link rel="stylesheet" href="/css/home.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 mb-3">Bienvenue {{ Auth::user()->name }} !</h1>
            <p class="lead text-muted">Plateforme d'apprentissage innovante utilisant la réalité virtuelle pour aider les enfants à améliorer leur prononciation.</p>
        </div>
    </div>

        @if(Auth::user()->role !== 'teacher')
        <!-- Options pour les étudiants -->
    <div class="student-cards-container">
        <div class="student-card-wrapper">
            <div class="card border-0 shadow-sm hover-card student-card">
                <div class="card-body d-flex flex-column align-items-center p-4">
                    <div class="card-image-wrapper mb-4">
                        <img src="{{ asset('images/vr-learning.jpg') }}" alt="Commencer l'apprentissage" class="img-fluid rounded-circle">
                    </div>
                    <div class="card-content text-center">
                    <h3 class="h4 mb-3">Commencer l'Apprentissage</h3>
                    <p class="text-muted mb-4">Plongez dans un environnement virtuel immersif pour améliorer votre prononciation de manière interactive et amusante.</p>
                    <a href="{{ route('jeu') }}" class="btn btn-primary btn-lg">Commencer</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-card-wrapper">
            <div class="card border-0 shadow-sm hover-card student-card">
                <div class="card-body d-flex flex-column align-items-center p-4">
                    <div class="card-image-wrapper mb-4">
                        <img src="{{ asset('images/progress-tracking.jpg') }}" alt="Mes tentatives" class="img-fluid rounded-circle">
                    </div>
                    <div class="card-content text-center">
                    <h3 class="h4 mb-3">Mes Tentatives</h3>
                    <p class="text-muted mb-4">Consultez votre historique d'apprentissage et suivez vos progrès au fil du temps.</p>
                    <a href="{{ route('results') }}" class="btn btn-outline-primary btn-lg">Voir mes résultats</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
        @else
        <!-- Options pour l'enseignant -->
    <div class="student-cards-container">
        <div class="student-card-wrapper">
            <div class="card border-0 shadow-sm hover-card student-card">
                <div class="card-body d-flex flex-column align-items-center p-4">
                    <div class="card-image-wrapper mb-4">
                        <img src="{{ asset('images/progress-tracking.jpg') }}" alt="Dashboard Enseignant" class="img-fluid rounded-circle">
                    </div>
                    <div class="card-content text-center">
                        <h3 class="h4 mb-3">Dashboard Enseignant</h3>
                        <p class="text-muted mb-4">Accédez au tableau de bord pour suivre les progrès de vos élèves et gérer leurs apprentissages.</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">Accéder au Dashboard</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-card-wrapper">
            <div class="card border-0 shadow-sm hover-card student-card">
                <div class="card-body d-flex flex-column align-items-center p-4">
                    <div class="card-image-wrapper mb-4">
                        <img src="{{ asset('images/letters-management.jpg') }}" alt="Gestion des Lettres" class="img-fluid rounded-circle">
                    </div>
                    <div class="card-content text-center">
                        <h3 class="h4 mb-3">Gestion des Lettres</h3>
                        <p class="text-muted mb-4">Gérez les lettres et leurs fichiers audio pour l'apprentissage des élèves.</p>
                        <a href="{{ route('admin.letters.index') }}" class="btn btn-primary btn-lg">Gérer les Lettres</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques ou informations supplémentaires -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body p-4">
                    <h4 class="mb-4">À propos de la plateforme</h4>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-vr-cardboard fa-2x text-primary me-3"></i>
                                <div>
                                    <h5 class="mb-1">Apprentissage VR</h5>
                                    <p class="text-muted mb-0">Technologie immersive</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-graduation-cap fa-2x text-primary me-3"></i>
                                <div>
                                    <h5 class="mb-1">Exercices Adaptés</h5>
                                    <p class="text-muted mb-0">Progression personnalisée</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chart-line fa-2x text-primary me-3"></i>
                                <div>
                                    <h5 class="mb-1">Suivi Détaillé</h5>
                                    <p class="text-muted mb-0">Analyse des progrès</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
