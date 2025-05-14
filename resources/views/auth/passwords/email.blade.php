@extends('layouts.app')

@section('styles')
    <link href="{{ asset('/css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="auth-container">
    <form method="POST" action="{{ route('password.email') }}" class="form">
        @csrf
        <p class="title">{{ __('Réinitialisation du mot de passe') }}</p>
        <p class="message">{{ __('Entrez votre email pour recevoir un lien de réinitialisation.') }}</p>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder=" ">
            <label for="email" class="form-label">{{ __('Adresse Email') }}</label>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn-primary">
            {{ __('Envoyer le lien') }}
        </button>

        <div class="bottom-links">
            <a class="btn-link" href="{{ route('login') }}">
                {{ __('Retour à la connexion') }}
            </a>
        </div>
    </form>
</div>
@endsection
