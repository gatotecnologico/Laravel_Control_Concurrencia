<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalesController extends Controller
{
    public function index()
    {
        $sucursal = Sucursal::find(1);
        return view('index', ['sucursal'=>$sucursal]);
    }
}
