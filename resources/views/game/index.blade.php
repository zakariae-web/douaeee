@extends('layouts.app')

@section('title', 'Jeu de Prononciation')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="game-container">
        <p id="instruction"></p>
        <button class="mx-auto btn btn-success mb-3" id="start">Dites la lettre</button>
        <div id="ar-container" style="width: 100%; height: 60vh; border: 1px solid black;"></div>  
        <p id="result"></p>
        <button class="mx-auto btn btn-warning mt-3" id="skip">Passer cette lettre</button>
    </div>

    
    
@endsection
