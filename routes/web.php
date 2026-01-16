<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DevisController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Route pour le formulaire WIZARD Livewire (page principale - NOUVELLE VERSION)
Route::get('/', function () {
    return view('devis-wizard');
})->name('devis.wizard');

// Route pour le formulaire classique (ancienne version)
Route::get('/devis-classique', [DevisController::class, 'index'])->name('devis.classique');

// Route pour traiter l'envoi du formulaire classique
Route::post('/devis/store', [DevisController::class, 'store'])->name('devis.store');