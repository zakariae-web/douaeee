@extends('layouts.app')

@section('styles')
    <link href="{{ asset('/css/auth.css') }}" rel="stylesheet">
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
