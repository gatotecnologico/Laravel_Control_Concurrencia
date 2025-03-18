<?php

namespace App\Models;

use App\Database\ServiciosTecnicos;
use Illuminate\Support\Facades\DB;
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

    public function abrirCaja($sucursal)
    {
        $sucursal_id = $sucursal->id;
        if ($sucursal->abierta === 0) {
            foreach ($this->denominaciones as $denominacion) {
                $existencia = rand(1, 5);
                $this->db->generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal);
            }
            return 'Caja Abierta Exitosamente';
        } else if ($sucursal->abierta === 1) {
            return 'No se puede volver a abrir la caja';
        }
    }

    public function agregarBilletes($sucursal)
    {
        if ($sucursal->abierta === 0) {
            return 'La caja debe estar abierta para realizar esta operación';
        }

        $sucursal_id = $sucursal->id;
        foreach ($this->denominaciones as $denominacion) {
            $existencia = rand(1, 5);
            $this->db->agregarBilletes($sucursal_id, $denominacion, $existencia);
        }
        return 'Billetes agregados exitosamente';
    }

    public function cambiarCheque($sucursal, $importe)
    {
        if ($sucursal->abierta === 0) {
            return ['error' => 'La caja debe estar abierta para realizar esta operación'];
        }

        if ($importe <= 0) {
            return ['error' => 'El importe debe ser mayor a cero'];
        }

        // Obtener los billetes disponibles en la sucursal
        $resultado = $this->db->obtenerBilletes($sucursal->id);

        if (isset($resultado['error'])) {
            return $resultado;
        }

        $denominaciones = [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
        $billetes = collect($resultado['billetes'])->keyBy('denominacion'); // Indexar por denominación
        $billetes_entregar = [];
        $importe_restante = $importe;

        DB::beginTransaction();
        try {
            foreach ($denominaciones as $denominacion) {
                if (isset($billetes[$denominacion])) {
                    $cantidad = min(floor($importe_restante / $denominacion), $billetes[$denominacion]->existencia);

                    if ($cantidad > 0) {
                        $billetes_entregar[$denominacion] = $cantidad;
                        $importe_restante -= $denominacion * $cantidad;

                        DB::table('tellers')
                            ->where('sucursal', $sucursal->id)
                            ->where('denominacion', $denominacion)
                            ->decrement('existencia', $cantidad, ['entregados' => DB::raw("entregados + $cantidad")]);
                    }
                }
            }

            if ($importe_restante > 0) {
                DB::rollBack();
                return ['error' => 'No hay denominaciones disponibles para entregar el monto exacto'];
            }

            DB::commit();

            // Construir el mensaje de éxito
            $mensaje = "Cheque cambiado exitosamente por $$importe\n";
            $mensaje .= "Desglose:\n";
            foreach ($billetes_entregar as $denominacion => $cantidad) {
                $tipo = $denominacion >= 100 ? "billetes" : "monedas";
                $mensaje .= "$cantidad $tipo de $$denominacion\n";
            }

            return ['success' => true, 'message' => $mensaje];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Error durante el canje del cheque: ' . $e->getMessage()];
        }
    }
}
