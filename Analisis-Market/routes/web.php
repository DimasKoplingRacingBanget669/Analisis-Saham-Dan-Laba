<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('Index');
})->name('Index');

Route::get('/Search', [ScrapingController::class, '_HandlePencarian'])->name('cari-data');
Route::get('/Statistik/{Symbol}', [ScrapingController::class, '_HandleStatistik'])->name('statistik');
Route::get('/Analisis/{Symbol}', [ScrapingController::class, '_HandleAnalisis'])->name('analisis');
Route::get('/finansial/{Symbol}', [ScrapingController::class, '_HandleFinansial'])->name('finansial');
Route::get('/neraca/{Symbol}', [ScrapingController::class, '_HandleNeraca'])->name('neraca');
Route::get('/arus-kas/{Symbol}', [ScrapingController::class, '_HandleArus_Khas'])->name('arus-kas');
Route::get('/profile/{Symbol}', [ScrapingController::class, '_HandleProfile'])->name('profile');


