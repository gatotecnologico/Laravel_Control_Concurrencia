<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\CajasController;

Route::get('/', [SucursalesController::class, 'index']);
Route::post('/{sucursal}/abrir', [CajasController::class, 'abrirCaja'])->name('teller.abrirCaja')->middleware('web');
Route::post('/{sucursal}/agregar', [CajasController::class, 'agregarBilletes'])->name('teller.agregarBilletes')->middleware('web');
Route::post('/{sucursal}/canjear', [CajasController::class, 'cambiarCheque'])->name('teller.cambiarCheque')->middleware('web');
