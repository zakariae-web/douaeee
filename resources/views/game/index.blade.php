@extends('layouts.app')

@section('title', 'Jeu de Prononciation')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="game-container">
        <h2>Prononcez la lettre affichée</h2>
        <div id="ar-container" style="width: 600px; height: 400px; border: 1px solid black;"></div>
        <button id="start">Dites la lettre</button>
        <p id="result"></p>
    </div>

    
    
@endsection
