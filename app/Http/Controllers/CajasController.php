<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class CajasController extends Controller
{
    private static $sucursal;
    private static $cajero;

    function __construct() {
        if(self::$sucursal === null){
            self::$sucursal = new Sucursal();
        }

        if(self::$cajero === null){
            self::$cajero = new Caja();
        }
    }

    public function abrirCaja($sucursal_id)
    {
        $sucursal = self::$sucursal->getSucursal($sucursal_id);
        $mensaje = self::$cajero->abrirCaja($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = self::$sucursal->getSucursal($sucursal_id);
        $mensaje = self::$cajero->agregarBilletes($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function cambiarCheque(Request $request, $sucursal_id) {
        $sucursal = self::$sucursal->getSucursal($sucursal_id);
        $importe = $request->input('monto');
        $resultado = self::$cajero->cambiarCheque($sucursal, $importe);

        return redirect()->back()->with(
            'message',
            isset($resultado['error']) ? $resultado['error'] : $resultado['message']
        );
    }
}
