@extends('layouts.app')
@section('contenu')
    <h2>Modifier la tâche</h2>
    <form action="/taches/{{ $tache->id }}" method="POST">
        @csrf @method('PUT')
        <p><label>Titre</label><input type="text" name="titre" value="{{ $tache->titre }}"></p>
        @error('titre')<p style="color:#ef4444;font-size:0.9rem">{{ $message }}</p>@enderror
        <p><label>Description</label><textarea name="description" rows="4">{{ $tache->description }}</textarea></p>
        <p><label><input type="checkbox" name="terminee" value="1" {{ $tache->terminee ? 'checked' : '' }}> Terminée</label></p>
        <div class="form-actions">
            <button class="btn btn-blue">Enregistrer</button>
            <a href="/taches" class="btn" style="color:var(--muted)">Annuler</a>
        </div>
    </form>
@endsection
