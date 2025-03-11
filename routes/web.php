<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\TellersController;

Route::get('/', [BranchesController::class, 'index']);
Route::post('/{sucursal}/abrir', [TellersController::class, 'abrirCaja'])->name('teller.abrirCaja')->middleware('web');
Route::post('/{sucursal}/agregar', [TellersController::class, 'agregarBilletes'])->name('teller.agregarBilletes')->middleware('web');
