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
    <link rel="stylesheet" href="/css/admin/letters/index.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('content')
<div class="container mt-5">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Lettres</h6>
                            <h2 class="mb-0">{{ $letters->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-font"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Stages Actifs</h6>
                            <h2 class="mb-0">{{ $letters->pluck('stage_id')->unique()->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Fichiers Audio</h6>
                            <h2 class="mb-0">{{ $letters->whereNotNull('audio_path')->count() }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-music"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-spell-check me-1"></i>
                        Gestion des Lettres
                    </h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLetterModal">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter une lettre
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 80px;">Lettre</th>
                                    <th>Stage</th>
                                    <th>Audio</th>
                                    <th class="text-center" style="width: 120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($letters as $letter)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-5 px-3 py-2">{{ $letter->letter }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-layer-group me-1"></i>
                                                {{ $letter->stage->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($letter->audio_path)
                                                <div class="d-flex align-items-center">
                                                    <audio controls class="me-2" style="height: 32px;">
                                                        <source src="{{ asset($letter->audio_path) }}" type="audio/mpeg">
                                                        Votre navigateur ne supporte pas l'élément audio.
                                                    </audio>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Audio présent
                                                    </span>
                                                </div>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Aucun audio
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editLetterModal-{{ $letter->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.letters.destroy', $letter) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ms-2 btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette lettre ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                         @endforeach
                            </tbody>
                        </table>
                        
                    </div>

                    <div class="mt-4">
                        <div class="paginate">
                            <button class="prevBtn" onclick="window.location.href='{{ $letters->previousPageUrl() }}'" {{ $letters->onFirstPage() ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-left me-2"></i>
                                <span class="prevBtn-text">Précédent</span>
                            </button>
                            <div class="containerBtns">
                                <div class="leftContainer">
                                    @for($i = max(1, $letters->currentPage() - 2); $i < $letters->currentPage(); $i++)
                                        <button onclick="window.location.href='{{ $letters->url($i) }}'">{{ $i }}</button>
                                    @endfor
                                </div>
                                <button class="activeBtn">{{ $letters->currentPage() }}</button>
                                <div class="rightContainer">
                                    @for($i = $letters->currentPage() + 1; $i <= min($letters->lastPage(), $letters->currentPage() + 2); $i++)
                                        <button onclick="window.location.href='{{ $letters->url($i) }}'">{{ $i }}</button>
                                    @endfor
                                </div>
                            </div>
                            <button class="nextBtn" onclick="window.location.href='{{ $letters->nextPageUrl() }}'" {{ !$letters->hasMorePages() ? 'disabled' : '' }}>
                                <span class="nextBtn-text">Suivant</span>
                                <i class="fas fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                        <div class="paginate-details text-center mt-3 text-muted">
                            Page <span class="fw-bold text-primary">{{ $letters->currentPage() }}</span> sur <span class="fw-bold">{{ $letters->lastPage() }}</span> — Total : <span class="fw-bold">{{ $letters->total() }}</span> lettres
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($letters as $letter)
<div class="modal fade" id="editLetterModal-{{ $letter->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-edit me-2 text-primary"></i>
                                                        Modifier la lettre {{ $letter->letter }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.letters.update', $letter) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" name="letter" required maxlength="50" 
                                                                   value="{{ $letter->letter }}" placeholder="Entrez une lettre">
                                                        </div>
                                                        <div class="mb-3">
                                                            <select class="form-select" name="stage_id" required>
                                                                <option value="">Sélectionnez un stage</option>
                                                                @foreach($stages as $stage)
                                                                    <option value="{{ $stage->id }}" {{ $letter->stage_id == $stage->id ? 'selected' : '' }}>
                                                                        {{ $stage->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="file" class="form-control" name="audio" accept=".mp3,.wav">
                                                            @if($letter->audio_path)
                                                                <div class="form-text">
                                                                    <i class="fas fa-info-circle me-1"></i>
                                                                    Un fichier audio existe déjà. En télécharger un nouveau le remplacera.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-1"></i>
                                                            Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save me-1"></i>
                                                            Enregistrer
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

<!-- Modal Ajout -->
<div class="modal fade" id="addLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2 text-primary"></i>
                    Ajouter une lettre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.letters.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="letter" required maxlength="50" placeholder="Entrez une lettre">
                    </div>
                    <div class="mb-3">
                        <select class="form-select" name="stage_id" required>
                            <option value="">Sélectionnez un stage</option>
                            @foreach($stages as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="file" class="form-control" name="audio" accept=".mp3,.wav" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection 