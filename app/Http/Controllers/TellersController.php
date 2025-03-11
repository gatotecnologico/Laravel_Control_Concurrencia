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
        $mensaje = $cajero->abrirCaja($sucursal, $cajero);
        return redirect()->back()->with('message', $mensaje);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = Branch::firstOrCreate(
            ['id' => $sucursal_id],
            [
                'abierta' => false,
                'nombre' => 'Sucursal ' . $sucursal_id
            ]
        );

        $cajero = new Teller();
        $cajero->agregarBilletes($sucursal);

        return redirect()->back()->with('message', 'Billetes agregados exitosamente');
    }
}
