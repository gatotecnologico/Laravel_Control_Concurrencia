<?php

namespace App\Http\Controllers;
use App\Models\Teller;
use App\Models\Branch;
use Illuminate\Http\Request;

class TellersController extends Controller
{
    public function abrirCaja($sucursal_id) {
        $sucursal = Branch::firstOrCreate(
            ['id' => $sucursal_id],
            ['abierta' => false]
        );
        $cajero = new Teller();
        $mensaje = $cajero->abrirCaja($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = Branch::firstOrCreate(
            ['id' => $sucursal_id],
            ['abierta' => false]
        );
        $cajero = new Teller();
        $mensaje = $cajero->agregarBilletes($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function cambiarCheque(Request $request, $sucursal_id) {
        $sucursal = Branch::firstOrCreate(
            ['id' => $sucursal_id],
            ['abierta' => false]
        );
        
        $importe = $request->input('monto');
        
        if (!$importe || $importe <= 0) {
            return redirect()->back()->with('message', 'El monto debe ser mayor a cero');
        }

        $cajero = new Teller();
        $resultado = $cajero->cambiarCheque($sucursal, $importe);

        return redirect()->back()->with('message', 
            isset($resultado['error']) ? $resultado['error'] : $resultado['message']
        );
    }
}
