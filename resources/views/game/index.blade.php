@extends('layouts.app')

@section('title', 'Jeu de Prononciation')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="game-container">
        <p id="instruction"></p>
        <button class="mx-auto btn btn-success mb-3" id="start">Dites la lettre</button>
        <div id="ar-container" style="width: 100%; height: 60vh; border: 1px solid black;"></div>  
        <p id="result"></p>
<div class="form-group mb-3 text-center">
    <label for="stage-select">Choisissez le niveau :</label>
    <select id="stage-select" class="form-control w-auto d-inline-block ms-2">
        <option value="1">Niveau 1 - Lettres simples</option>
        <option value="2">Niveau 2 - Lettres complexes</option>
        <option value="3">Niveau 3 - Mots simples</option>
        <option value="4">Niveau 4 - Mots complexes</option>
    </select>
</div>


        <button class="mx-auto btn btn-warning mt-3" id="skip">Passer cette lettre</button>
    </div>

    
    
@endsection
