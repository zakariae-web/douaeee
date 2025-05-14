@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="auth-container">
    <form method="POST" action="{{ route('register') }}" class="form">
        @csrf
        <p class="title">Inscription</p>
        <p class="message">Créez votre compte pour accéder à toutes nos fonctionnalités.</p>

        <div class="form-group">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder=" ">
            <label for="name" class="form-label">{{ __('Nom complet') }}</label>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autocomplete="email" placeholder=" ">
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
            <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
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
            {{ __("S'inscrire") }}
        </button>

        <div class="bottom-links">
            {{ __('Vous avez déjà un compte?') }}
            <a class="btn-link" href="{{ route('login') }}">
                {{ __('Se connecter') }}
            </a>
        </div>
    </form>
</div>
@endsection
