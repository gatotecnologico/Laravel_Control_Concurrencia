<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modelo;

class Controlador extends Controller
{
    public function abrirCaja(Request $request)
    {
        $id_sucursal = $request->input('id_sucursal');
        if (Modelo::abrirCaja($id_sucursal)) {
            return response()->json(['message' => 'Caja abierta exitosamente.']);
        }
        return response()->json(['message' => 'La caja ya ha sido abierta.'], 400);
    }

    public function agregarBilletes(Request $request)
    {
        $id_sucursal = $request->input('id_sucursal');
        Modelo::agregarBilletes($id_sucursal);
        return response()->json(['message' => 'Billetes agregados correctamente.']);
    }

    public function canjearCheque(Request $request)
    {
        $id_sucursal = $request->input('id_sucursal');
        $monto = $request->input('monto');

        $resultado = Modelo::canjearCheque($id_sucursal, $monto);

        if ($resultado === false) {
            return response()->json(['message' => 'No es posible canjear el cheque con los billetes disponibles.'], 400);
        }

        return response()->json([
            'message' => 'Cheque canjeado exitosamente.',
            'billetes_entregados' => $resultado
        ]);
    }
}
