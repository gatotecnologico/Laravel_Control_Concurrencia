<?php

namespace App\Http\Controllers;
use App\Models\Teller;
use Illuminate\Http\Request;

class TellersController extends Controller
{
    public function abrirCaja($cajero) {
        $cajero = Teller::find($cajero);
        $cajero->abrirCaja($cajero);

        // return response()->json($cajero, 200);;
    }
}
