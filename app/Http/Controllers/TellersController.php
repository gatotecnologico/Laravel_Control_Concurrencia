<?php

namespace App\Http\Controllers;
use App\Models\Teller;
use App\Models\Branch;
use Illuminate\Http\Request;

class TellersController extends Controller
{
    private $sucursal;
    private $cajero;

    function __construct() {
        $this->sucursal = new Branch();
        $this->cajero = new Teller();
    }

    public function abrirCaja($sucursal_id) {
        $sucursal = $this->sucursal->getBranch($sucursal_id);
        $mensaje = $this->cajero->abrirCaja($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function agregarBilletes($sucursal_id) {
        $sucursal = $this->sucursal->getBranch($sucursal_id);
        $mensaje = $this->cajero->agregarBilletes($sucursal);
        return redirect()->back()->with('message', $mensaje);
    }

    public function cambiarCheque(Request $request, $sucursal_id) {
        $sucursal = $this->sucursal->getBranch($sucursal_id);
        $importe = $request->input('monto');

        if (!$importe || $importe <= 0) {
            return redirect()->back()->with('message', 'El monto debe ser mayor a cero');
        }
        $resultado = $this->cajero->cambiarCheque($sucursal, $importe);

        return redirect()->back()->with('message',
            isset($resultado['error']) ? $resultado['error'] : $resultado['message']
        );
    }
}
