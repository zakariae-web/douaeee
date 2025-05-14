@extends('layouts.app')

@section('title', "Tentatives de {$user->name}")

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section avec animation -->
            <div class="text-center mb-5 results-header">
                <h2 class="display-5 mb-3">📋 Tentatives de <strong>{{ $user->name }}</strong></h2>
                <p class="text-muted lead">Historique détaillé des tentatives de l'utilisateur</p>
                <div class="mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>

            @if ($attempts->isEmpty())
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-clipboard-list fa-4x text-muted"></i>
                    </div>
                    <h3 class="h4 mb-3">Aucune tentative enregistrée</h3>
                    <p class="text-muted mb-4">Cet utilisateur n'a pas encore fait de tentatives.</p>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="row mb-5">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="stats-icon mb-3 text-success">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h3 class="h2 mb-2">{{ $attempts->where('success', true)->count() }}</h3>
                                <p class="text-muted mb-0">Réussites</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm stats-card">
                            <div class="card-body text-center p-4">
                                <div class="stats-icon mb-3 text-danger">
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                                <h3 class="h2 mb-2">{{ $attempts->where('success', false)->count() }}</h3>
                                <p class="text-muted mb-0">Erreurs</p>
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
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Lettre attendue</th>
                                        <th class="px-4 py-3">Lettre prononcée</th>
                                        <th class="px-4 py-3">Résultat</th>
                                        <th class="px-4 py-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attempts as $index => $attempt)
                                        <tr class="result-row">
                                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3">
                                                <span class="letter-badge">{{ $attempt->letter }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $attempt->attempted_word ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if ($attempt->success)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                                        <i class="fas fa-check me-1"></i> Correct
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                                                        <i class="fas fa-times me-1"></i> Incorrect
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                                    {{ $attempt->created_at->format('d/m/Y H:i') }}
                                                </div>
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

<style>
.results-header {
    animation: fadeInDown 0.5s ease-out;
}

.stats-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    background: rgba(var(--bs-success-rgb), 0.1);
}

.stats-card:nth-child(2) .stats-icon {
    background: rgba(var(--bs-danger-rgb), 0.1);
}

.letter-badge {
    background: linear-gradient(45deg, #2196F3, #0D47A1);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1.1em;
}

.result-row {
    transition: background-color 0.2s ease;
}

.result-row:hover {
    background-color: rgba(0,0,0,0.02);
}

.empty-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 3rem;
}

.empty-state-icon {
    color: #6c757d;
    opacity: 0.5;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation pour les cartes de statistiques */
.stats-card {
    animation: fadeInUp 0.5s ease-out;
    animation-fill-mode: both;
}

.stats-card:nth-child(1) { animation-delay: 0.1s; }
.stats-card:nth-child(2) { animation-delay: 0.2s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Style pour la pagination */
.paginate {
    margin-top: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.paginate button {
    transition: all 0.3s ease;
}

.paginate button:hover {
    background-color: #f8f9fa;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}
</style>

<script>
    const countPage = {{ $attempts->lastPage() }};
    const currentPage = {{ $attempts->currentPage() }};
</script>
<script src="{{ asset('js/pagination.js') }}"></script>
@endsection
