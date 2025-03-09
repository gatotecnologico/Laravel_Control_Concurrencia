<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;

Route::get('/', [BranchController::class, 'index']);
