<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TacheController;

Route::redirect('/', '/taches');

Route::resource('taches', TacheController::class);