@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 mb-3">Bienvenue {{ Auth::user()->name }} !</h1>
            <p class="lead text-muted">Plateforme d'apprentissage innovante utilisant la réalité virtuelle pour aider les enfants à améliorer leur prononciation.</p>
        </div>
    </div>

    <div class="row g-4">
        @if(Auth::user()->role !== 'teacher')
        <!-- Options pour les étudiants -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center p-5">
                    <img src="{{ asset('images/vr-learning.jpg') }}" alt="Commencer l'apprentissage" class="img-fluid mb-4" style="max-height: 200px;">
                    <h3 class="h4 mb-3">Commencer l'Apprentissage</h3>
                    <p class="text-muted mb-4">Plongez dans un environnement virtuel immersif pour améliorer votre prononciation de manière interactive et amusante.</p>
                    <a href="{{ route('jeu') }}" class="btn btn-primary btn-lg">Commencer</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body text-center p-5">
                    <img src="{{ asset('images/progress-tracking.jpg') }}" alt="Mes tentatives" class="img-fluid mb-4" style="max-height: 200px;">
                    <h3 class="h4 mb-3">Mes Tentatives</h3>
                    <p class="text-muted mb-4">Consultez votre historique d'apprentissage et suivez vos progrès au fil du temps.</p>
                    <a href="{{ route('results') }}" class="btn btn-outline-primary btn-lg">Voir mes résultats</a>
                </div>
            </div>
        </div>
        @else
        <!-- Options pour l'enseignant -->
        <div class="row">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-5">
                        <img src="{{ asset('images/progress-tracking.jpg') }}" style="height: 250px" alt="Dashboard Enseignant" class="img-fluid mb-4" style="max-height: 200px;">
                        <h3 class="h4 mb-3">Dashboard Enseignant</h3>
                        <p class="text-muted mb-4">Accédez au tableau de bord pour suivre les progrès de vos élèves et gérer leurs apprentissages.</p>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">Accéder au Dashboard</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-5">
                        <img src="{{ asset('images/letters-management.jpg') }}" style="height: 250px" alt="Gestion des Lettres" class="img-fluid mb-4" style="max-height: 200px;">
                        <h3 class="h4 mb-3">Gestion des Lettres</h3>
                        <p class="text-muted mb-4">Gérez les lettres et leurs fichiers audio pour l'apprentissage des élèves.</p>
                        <a href="{{ route('admin.letters.index') }}" class="btn btn-primary btn-lg">Gérer les Lettres</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

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

<style>
.hover-card {
    transition: transform 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
}
</style>
@endsection
