<?php

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


Route::get('/', [App\Http\Controllers\InfoController::class, 'view'])->name('info.view');
Route::get('/allData', [App\Http\Controllers\InfoController::class, 'alldata'])->name('info.alldata');
Route::get('/add', [App\Http\Controllers\InfoController::class, 'add'])->name('info.add');
Route::post('/store', [App\Http\Controllers\InfoController::class, 'store'])->name('info.store');
Route::get('/edit/{id}', [App\Http\Controllers\InfoController::class, 'edit'])->name('info.edit');
Route::post('/update/{id}', [App\Http\Controllers\InfoController::class, 'update'])->name('info.update');
Route::get('/delete/{id}', [App\Http\Controllers\InfoController::class, 'delete'])->name('info.delete');