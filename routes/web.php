<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KuisionerController;
use App\Models\Respondent;

Route::get('/peta', function () {
    $titiks = Respondent::all();
    return view('peta', compact('titiks'));
});

Route::get('/', [KuisionerController::class, 'dashboard']); 
Route::get('/dashboard', [KuisionerController::class, 'dashboard'])->name('dashboard');

// FORM PILIH KATEGORI
Route::get('/form', [KuisionerController::class, 'form'])->name('form');
Route::get('/form/{kategori}', [KuisionerController::class, 'formByKategori'])->name('form.kategori');

// SUBMIT
Route::post('/submit', [KuisionerController::class, 'submit'])->name('submit');
