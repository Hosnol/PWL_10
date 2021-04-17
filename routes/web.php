<?php

use App\Http\Controllers\ArticleController;
use App\Models\Article;
use App\Models\Mahasiswa;
use App\Http\Controllers\MahasiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('articles', ArticleController::class);
Route::get('/article/cetak_pdf', [ArticleController::class, 'cetak_pdf']);
Route::resource('mahasiswa', MahasiswaController::class);
Route::get('/nilai/{mahasiswa}', [MahasiswaController::class,'hasil'])->name('mahasiswa.nilai');
Route::get('/cari',[MahasiswaController::class,'cari'])->name('mahasiswa.cari');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
