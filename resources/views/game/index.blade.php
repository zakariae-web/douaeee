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
    <link rel="stylesheet" href="/css/jeu/index.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection
@section('title', 'Jeu de Prononciation')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header Section -->
                <div class="text-center mb-4">
                    <h1 class="display-5 mb-3">Exercice de Prononciation</h1>
                    <p class="lead text-muted">Améliorez votre prononciation en réalité virtuelle</p>
                </div>

                <!-- Game Interface -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-4">
                        <!-- Instructions -->
                        <div class="text-center mb-4">
                            <div class="instruction-box p-3 bg-light rounded-3 mb-4">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <span id="instruction" class="fs-5">Préparez-vous...</span>
                            </div>
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <button class="btn btn-primary btn-lg px-5" id="start">
                                    <i class="fas fa-microphone me-2"></i>
                                    Dites la lettre
                                </button>
                            </div>
                        </div>
                        <div id="result-container" class="mt-4 mb-4 text-center d-none">
                            <div class="alert custom-alert alert-dismissible fade show" role="alert">
                                <div class="result-icon-wrapper">
                                    <i class="result-icon"></i>
                                 </div>
                                <div class="result-content">
                                    <span id="result-message"></span>
                                    <div class="pronunciation-text mt-2"></div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <!-- AR Container -->
                        <div class="ar-wrapper mb-4">
                            <div id="ar-container" class="rounded-3 overflow-hidden" style="width: 100%; height: 70vh; border: 2px solid #e9ecef; background-color: #f8f9fa;"></div>
                        </div>

                        <!-- Controls -->
                        <div class="d-flex justify-content-center mt-4">
                            <button class="btn btn-outline-warning btn-lg px-5" id="skip">
                                <i class="fas fa-forward me-2"></i>
                                Passer cette lettre
                            </button>
                        </div>

                        <!-- Result Message Container -->

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
