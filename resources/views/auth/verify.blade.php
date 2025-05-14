@extends('layouts.app')

@section('styles')
    <link href="{{ asset('/css/auth.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="auth-container">
    <div class="form">
        <p class="title">{{ __('Vérifiez votre adresse e-mail') }}</p>
        <p class="message">
            {{ __('Avant de continuer, veuillez vérifier votre e-mail pour un lien de vérification.') }}
            {{ __('Si vous n\'avez pas reçu l\'e-mail') }},
        </p>

        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
            </div>
        @endif

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-primary">
                {{ __('Cliquez ici pour en recevoir un autre') }}
            </button>
        </form>
    </div>
</div>
@endsection
