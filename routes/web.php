<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

Route::get('/form', [DataController::class, 'showForm'])->name('form.show');

Route::post('/form', [DataController::class, 'saveData'])->name('form.submit');

Route::get('/data', [DataController::class, 'showData'])->name('data.index');

Route::get('/', function () {
    return redirect()->route('form.show');
});
