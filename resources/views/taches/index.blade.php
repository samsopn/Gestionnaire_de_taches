@extends('layouts.app')
@section('contenu')
    @if(session('message'))
        <p class="success">{{ session('message') }}</p>
    @endif
    <div class="toolbar">
        <a href="/taches/create" class="btn btn-blue">+ Nouvelle tâche</a>
    </div>
    @if($taches->isEmpty())
        <p class="empty">Aucune tâche pour le moment. Créez-en une !</p>
    @else
    <div class="table-wrap">
    <table>
        <tr>
            <th class="col-titre">Titre</th>
            <th class="col-desc">Description</th>
            <th class="col-statut">Statut</th>
            <th class="col-actions">Actions</th>
        </tr>
        @foreach($taches as $tache)
        <tr>
            <td class="cell-titre"><strong>{{ $tache->titre }}</strong></td>
            <td class="cell-desc" title="{{ $tache->description }}">{{ $tache->description ?: '—' }}</td>
            <td class="col-statut">
                <span class="badge {{ $tache->terminee ? 'badge-done' : 'badge-pending' }}">
                    {{ $tache->terminee ? 'Terminée' : 'En cours' }}
                </span>
            </td>
            <td class="cell-actions">
                <a href="/taches/{{ $tache->id }}/edit" class="btn btn-blue">Modifier</a>
                <form action="/taches/{{ $tache->id }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-red">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    </div>
    @endif
@endsection