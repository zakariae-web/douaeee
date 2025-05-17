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
    <link rel="stylesheet" href="/css/welcome.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center min-vh-75 py-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">Apprentissage Innovant avec la Réalité Virtuelle</h1>
            <p class="lead text-muted mb-4">Une approche révolutionnaire pour aider les enfants ayant des difficultés de lecture et de prononciation grâce à la réalité virtuelle.</p>
            <div class="d-flex gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Commencer l'aventure</a>
                <a href="#features" class="btn btn-outline-secondary btn-lg">Découvrir</a>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="{{ asset('images/hero-vr.png') }}" alt="Illustration" class="img-fluid">
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-5">
        <h2 class="text-center mb-5">Nos Solutions Innovantes</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('images/vr-learning.jpg') }}" style="height:400px" class="card-img-top p-3" alt="Apprentissage VR">
                    <div class="card-body text-center">
                        <i class="fas fa-vr-cardboard fa-3x text-primary mb-3"></i>
                        <h3 class="h5">Apprentissage Immersif</h3>
                        <p class="text-muted">Un environnement virtuel engageant qui rend l'apprentissage de la lecture amusant et interactif.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('images/personalized-exercises.jpg') }}" style="height:400px" class="card-img-top p-3" alt="Exercices Personnalisés">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                        <h3 class="h5">Exercices Personnalisés</h3>
                        <p class="text-muted">Des exercices adaptés au niveau et aux besoins spécifiques de chaque enfant.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('images/progress-tracking.jpg') }}" style="height:400px" class="card-img-top p-3" alt="Suivi des Progrès">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                        <h3 class="h5">Suivi des Progrès</h3>
                        <p class="text-muted">Un tableau de bord détaillé pour suivre l'évolution et les accomplissements de votre enfant.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Call to Action -->
    <div class="text-center py-5">
        <h2 class="mb-4">Donnez à votre enfant les clés de la réussite</h2>
        <p class="lead text-muted mb-4">Rejoignez les familles qui ont déjà transformé l'apprentissage de leur enfant</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Commencer Gratuitement</a>
    </div>
</div>
@endsection
