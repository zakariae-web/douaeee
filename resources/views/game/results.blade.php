@extends('layouts.app')

@section('title', 'Vos Résultats')

@section('content')
<link href="{{ asset('css/pagination.css') }}" rel="stylesheet">

<div class="container mt-4">
    <h2 class="mb-4 text-center">📝 Historique de vos tentatives</h2>

    @if($attempts->isEmpty())
        <div class="alert alert-info text-center">
            Aucune tentative enregistrée pour le moment.
        </div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Lettre Cible</th>
                    <th>Lettre Prononcée</th>
                    <th>Résultat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $attempt->letter }}</strong></td>
                        <td>{{ $attempt->attempted_word ?: '—' }}</td>
                        <td>
                            @if($attempt->success)
                                <span class="badge bg-success">✔️ Correct</span>
                            @elseif($attempt->skipped)
                                <span class="badge bg-secondary">⏭️ Sauté</span>
                            @else
                                <span class="badge bg-danger">❌ Incorrect</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Conteneur personnalisé pour pagination --}}
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
    @endif
</div>

<script>
    const countPage = {{ $attempts->lastPage() }};
    const currentPage = {{ $attempts->currentPage() }};
</script>
<script src="{{ asset('js/pagination.js') }}"></script>
@endsection
