<?php

namespace App\Models;

use App\Database\ServiciosTecnicos;
use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    protected $primaryKey = ['sucursal', 'denominacion'];
    public $incrementing = false;
    protected $fillable = ['sucursal', 'denominacion'];

    public function __construct()
    {
        $this->db = new ServiciosTecnicos();
        $this->denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function abrirCaja($sucursal) {
        $sucursal_id = $sucursal->id;
        if($sucursal->abierta === 0) {
            foreach ($this->denominaciones as $denominacion) {
                $existencia = rand(5, 20);
                $this->db->generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal);
            }
            return 'Caja Abierta Exitosamente';
        }   else if($sucursal->abierta === 1) {
            return 'No se puede volver a abrir la caja';
        }
    }

    public function agregarBilletes($sucursal)
    {
        $sucursal_id = $sucursal->id;
        foreach ($this->denominaciones as $denominacion)
        {
            $existencia = rand(5, 20);
            $this->db->agregarBilletes($sucursal_id, $denominacion, $existencia);
        }
        return 'Billetes agregados exitosamente';
    }

    public function cambiarCheque($sucursal, $importe)
    {
        if (!$sucursal->abierta) {
            return ['error' => 'La caja debe estar abierta para realizar esta operación'];
        }

        $resultado = $this->db->cambiarCheque($sucursal->id, $importe, array_reverse($this->denominaciones));
        if (isset($resultado['error'])) {
            return $resultado;
        }

        $mensaje = "Cheque cambiado exitosamente por $importe\n";
        $mensaje .= "Billetes entregados:\n";
        foreach ($resultado['billetes'] as $denominacion => $cantidad) {
            $mensaje .= "$cantidad billetes de $$denominacion\n";
        }

        return ['success' => true, 'message' => $mensaje];
    }
}
