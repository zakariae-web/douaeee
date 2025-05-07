@extends('layouts.app')

@section('title', "Tentatives de {$user->name}")

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">
<div class="container py-4">
    <h2 class="mb-4 text-center">📋 Tentatives de : <strong>{{ $user->name }}</strong></h2>

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">← Retour au tableau de bord</a>
    </div>

    @if ($attempts->isEmpty())
        <div class="alert alert-info text-center">Aucune tentative enregistrée pour cet utilisateur.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Lettre attendue</th>
                        <th>Lettre prononcée</th>
                        <th>Résultat</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attempts as $index => $attempt)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $attempt->letter }}</td>
                            <td>{{ $attempt->attempted_word ?? '-' }}</td>
                            <td>
                                @if ($attempt->success)
                                    <span class="text-success fw-bold">✔️ Correct</span>
                                @else
                                    <span class="text-danger fw-bold">❌ Incorrect</span>
                                @endif
                            </td>
                            <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
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
            Page <span id="current-page"></span> sur {{ $attempts->lastPage() }} — {{ $attempts->total() }} tentatives
        </div>
    </div>
</div>

<script>
    const countPage = {{ $attempts->lastPage() }};
    const currentPage = {{ $attempts->currentPage() }};
</script>
<script src="{{ asset('js/pagination.js') }}"></script>
</div>
@endsection
