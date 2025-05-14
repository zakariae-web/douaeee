@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="auth-container">
    <form method="POST" action="{{ route('login') }}" class="form">
                        @csrf
        <p class="title">Connexion</p>
        <p class="message">Connectez-vous pour accéder à votre compte.</p>

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

        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="current-password" placeholder=" ">
            <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                                <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" 
                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                {{ __('Se souvenir de moi') }}
                                    </label>
                        </div>

        <button type="submit" class="btn-primary">
            {{ __('Se connecter') }}
                                </button>

        <div class="bottom-links">
                                @if (Route::has('password.request'))
                <a class="btn-link" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié?') }}
                                    </a>
                                @endif
            
            @if (Route::has('register'))
                <div style="margin-top: 10px;">
                    {{ __("Vous n'avez pas de compte?") }} 
                    <a class="btn-link" href="{{ route('register') }}">
                        {{ __("S'inscrire") }}
                    </a>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection
