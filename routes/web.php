<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\TellersController;

Route::get('/', [BranchesController::class, 'index']);
Route::get('/{sucursal}', [TellersController::class, 'abrirCaja'])->name('teller.abrirCaja');
Route::post('/{sucursal}/agregar', [TellersController::class, 'agregarBilletes'])->name('teller.agregarBilletes');
