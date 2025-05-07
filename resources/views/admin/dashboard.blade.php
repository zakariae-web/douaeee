@extends('layouts.app')

@section('title', 'Tableau de bord enseignant')

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">
<div class="container py-4">
    <h2 class="mb-4 text-center">🎓 Tableau de bord de l'enseignant</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>👤 Étudiant</th>
                    <th>📧 Email</th>
                    <th>📊 Nombre de tentatives</th>
                    <th>🔍 Voir les tentatives</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->pronunciation_attempts_count }}</td>
                        <td>
                            <a href="{{ route('admin.user.attempts', $user->id) }}" class="btn btn-outline-primary btn-sm">
                                Voir les tentatives
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Aucun étudiant trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="container my-4">
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
        <div class="paginate-details text-center mt-2">
            Page <span id="current-page"></span> sur {{ $users->lastPage() }} — {{ $users->total() }} étudiants
        </div>
    </div>
    <script>
    const countPage = {{ $users->lastPage() }};
    const currentPage = {{ $users->currentPage() }};
</script>
<script src="{{ asset('js/pagination.js') }}"></script>

@endsection
