@extends('layouts.app')

@section('title', 'Résultats')

@section('content')
    <h2>Vos Tentatives</h2>
    <table>
        <thead>
            <tr>
                <th>Lettre</th>
                <th>Succès</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attempts as $attempt)
                <tr>
                    <td>{{ $attempt->letter }}</td>
                    <td>{{ $attempt->success ? '✔️' : '❌' }}</td>
                    <td>{{ $attempt->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
