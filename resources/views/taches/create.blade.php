@extends('layouts.app')
@section('contenu')
    <h2>Nouvelle tâche</h2>
    <form action="/taches" method="POST">
        @csrf
        <p><label>Titre</label><input type="text" name="titre"></p>
        @error('titre')<p style="color:#ef4444;font-size:0.9rem">{{ $message }}</p>@enderror
        <p><label>Description</label><textarea name="description" rows="4"></textarea></p>
        <div class="form-actions">
            <button class="btn btn-blue">Enregistrer</button>
            <a href="/taches" class="btn" style="color:var(--muted)">Annuler</a>
        </div>
    </form>
@endsection
