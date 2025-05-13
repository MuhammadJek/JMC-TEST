<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::resource('barang', BarangController::class);

Route::middleware(['isAdmin', 'locked'])->group(function () {
    Route::resource('category', CategoryController::class)->except('create');
    Route::resource('subcategory', SubCategoryController::class)->except('create');
    Route::resource('users', UserController::class)->except('create');

    Route::get('user-lock/{id}', [UserController::class, 'updateLock'])->name('user.lock');
    Route::get('barang-activation/{id}', [BarangController::class, 'updateActivation'])->name('barang.activation');
    Route::get('barang-cetak/{id}', [BarangController::class, 'cetakBarang'])->name('barang.cetak');
    Route::delete('barang-delete/{id}', [BarangController::class, 'deleteBarang'])->name('barang.deletes');
});
