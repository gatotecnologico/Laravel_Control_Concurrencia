<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TellerController;

Route::get('/', [BranchController::class, 'index']);
Route::put('/{id}/abrir', [TellerController::class], 'abrirCaja');
