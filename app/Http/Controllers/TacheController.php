<?php 
namespace App\Http\Controllers; 
use App\Models\Tache; 
use Illuminate\Http\Request; 
 
class TacheController extends Controller 

{ 
    public function index() { 
        $taches = Tache::all(); 
        return view('taches.index', compact('taches')); 
    } 
 
    public function create() { 
        return view('taches.create'); 
    }

public function store(Request $request) { 
        $request->validate([ 
            'titre' => 'required|max:255', 
            'description' => 'nullable', 
        ]); 
        Tache::create($request->all()); 
        return redirect('/taches')->with('message', 'Tâche ajoutée 
!'); 
    } 
 
    public function edit($id) { 
        $tache = Tache::find($id); 
        return view('taches.edit', compact('tache')); 
    } 
 
    public function update(Request $request, $id) { 
        $request->validate(['titre' => 'required|max:255']); 
        $tache = Tache::find($id); 
        $tache->update([ 
            'titre'       => $request->titre, 
            'description' => $request->description, 
            'terminee'    => $request->has('terminee'), 
        ]); 
        return redirect('/taches')->with('message', 'Tâche modifiée 
!'); 
    } 
 
    public function destroy($id) { 
        Tache::find($id)->delete(); 
        return redirect('/taches')->with('message', 'Tâche supprimée 
!'); 
    } 
} 