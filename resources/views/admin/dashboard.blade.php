@extends('layouts.app')

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
                                    <i class="fas fa-chart-bar text-primary me-2"></i>
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
                                <td colspan="4" class="text-center py-5">
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

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
}

.bg-soft-primary {
    background-color: rgba(52, 144, 220, 0.1);
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    font-size: 0.875rem;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.form-control:focus {
    border-color: #3490dc;
    box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.25);
}

.input-group-text {
    border-radius: 10px 0 0 10px;
}

.form-control {
    border-radius: 0 10px 10px 0;
}

.btn-outline-primary {
    border-width: 2px;
}

.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(52, 144, 220, 0.2);
}

@media (max-width: 768px) {
    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }
    
    .table td, .table th {
        padding: 0.75rem;
    }
}
</style>

<script>
    const countPage = {{ $users->lastPage() }};
    const currentPage = {{ $users->currentPage() }};
</script>

<script src="{{ asset('js/pagination.js') }}"></script>
@endsection
