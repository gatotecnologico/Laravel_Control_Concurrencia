<?php

namespace App\Http\Controllers;
use App\Models\Teller;
use App\Models\Branch;
use Illuminate\Http\Request;

class TellersController extends Controller
{
    public function abrirCaja($sucursal_id) {
        $sucursal = Branch::find($sucursal_id);
        $cajero = new Teller();
        $cajero->abrirCaja($sucursal, $cajero);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = Branch::find($sucursal_id);
        $cajero = new Teller();
        $cajero->agregarBilletes($sucursal);
    }
}
