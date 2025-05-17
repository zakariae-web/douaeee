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
