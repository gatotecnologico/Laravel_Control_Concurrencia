<?php

namespace App\Http\Controllers;
use App\Models\Teller;
use Illuminate\Http\Request;

class TellerController extends Controller
{
    public function abrirCaja(int $id) {
        $cajero = Teller::findOrFail($id);
        $cajero->abrirCaja($id);
    }
}
