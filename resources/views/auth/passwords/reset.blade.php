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
    <link rel="stylesheet" href="/css/auth.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
@endsection


@section('content')
<div class="auth-container">
    <form method="POST" action="{{ route('password.update') }}" class="form">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <p class="title">{{ __('Réinitialisation du mot de passe') }}</p>
        <p class="message">{{ __('Entrez votre nouveau mot de passe.') }}</p>

        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder=" ">
            <label for="email" class="form-label">{{ __('Adresse Email') }}</label>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="new-password" placeholder=" ">
            <label for="password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="password-confirm" type="password" class="form-control" 
                   name="password_confirmation" required autocomplete="new-password" placeholder=" ">
            <label for="password-confirm" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
        </div>

        <button type="submit" class="btn-primary">
            {{ __('Réinitialiser le mot de passe') }}
        </button>

        <div class="bottom-links">
            <a class="btn-link" href="{{ route('login') }}">
                {{ __('Retour à la connexion') }}
            </a>
        </div>
    </form>
</div>
@endsection
