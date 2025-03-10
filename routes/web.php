<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\TellersController;

Route::get('/', [BranchesController::class, 'index']);
Route::get('/{cajero}', [TellersController::class, 'abrirCaja'])->name('teller.abrirCaja');;
