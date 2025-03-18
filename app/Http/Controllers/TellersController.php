<?php

namespace App\Http\Controllers;

use App\Models\Teller;
use App\Models\Branch;
use Illuminate\Http\Request;

class TellersController extends Controller
{
    private static $sucursal;
    private static $cajero;

    function __construct() {
        if(self::$sucursal === null){
            self::$sucursal = new Branch();
        }

        if(self::$cajero === null){
            self::$cajero = new Teller();
        }
    }

    public function abrirCaja($sucursal_id)
    {
        $sucursal = $this->sucursal->getBranch($sucursal_id);
        $mensaje = $this->cajero->abrirCaja($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = self::$sucursal->getBranch($sucursal_id);
        $mensaje = self::$cajero->agregarBilletes($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function cambiarCheque(Request $request, $sucursal_id) {
        $sucursal = self::$sucursal->getBranch($sucursal_id);
        $importe = $request->input('monto');
        $resultado = $this->cajero->cambiarCheque($sucursal, $importe);

        return redirect()->back()->with(
            'message',
            isset($resultado['error']) ? $resultado['error'] : $resultado['message']
        );
    }
}
