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

// Route pour afficher le formulaire de devis
Route::get('/', [DevisController::class, 'index'])->name('devis.index');

// Route pour traiter l'envoi du formulaire
Route::post('/devis/store', [DevisController::class, 'store'])->name('devis.store');