<?php

namespace App\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ServiciosTecnicos extends Model
{
    public function generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal)
    {
        // dump($sucursal_id);
        DB::table('tellers')->insert([
            'sucursal' => $sucursal_id,
            'denominacion' => $denominacion,
            'existencia' => $existencia,
            'entregados' => 0,
        ]);
        $sucursal->abierta = true;
        $sucursal->save();
    }

    public function agregarBilletes($sucursal_id, $denominacion, $existencia)
    {
        $actual = DB::table('tellers')->where('sucursal', $sucursal_id)->where('denominacion', $denominacion)->first();
        DB::table('tellers')->upsert([
            'sucursal' => $sucursal_id,
            'denominacion' => $denominacion,
            'existencia' => ($actual ? $actual->existencia : 0) + $existencia,
            'entregados' => $actual ? $actual->entregados : 0,
        ], ['sucursal', 'denominacion'], ['existencia']);
    }


    public function cambiarCheque($sucursal_id, $importe)
    {
        if ($importe <= 0) {
            return ['error' => 'El importe debe ser mayor a cero'];
        }

        $denominaciones = [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
        $billetes_entregar = [];
        $importe_restante = $importe;

        // Obtener todas las denominaciones disponibles en una sola consulta
        $billetes = DB::table('tellers')
            ->where('sucursal', $sucursal_id)
            ->whereIn('denominacion', $denominaciones)
            ->get()
            ->keyBy('denominacion');  // Para acceder rápidamente por clave

        DB::beginTransaction();

        try {
            foreach ($denominaciones as $denominacion) {
                if (!isset($billetes[$denominacion]) || $billetes[$denominacion]->existencia <= 0) {
                    continue;
                }

                $cantidad_necesaria = floor($importe_restante / $denominacion);
                $cantidad_disponible = min($cantidad_necesaria, $billetes[$denominacion]->existencia);

                if ($cantidad_disponible > 0) {
                    $billetes_entregar[$denominacion] = $cantidad_disponible;
                    $importe_restante -= ($denominacion * $cantidad_disponible);

                    // Actualizar existencias y entregados
                    DB::table('tellers')->where('sucursal', $sucursal_id)
                        ->where('denominacion', $denominacion)
                        ->update([
                            'existencia' => $billetes[$denominacion]->existencia - $cantidad_disponible,
                            'entregados' => $billetes[$denominacion]->entregados + $cantidad_disponible,
                        ]);
                }
            }

            // Verificar si se logró completar el importe exacto
            if ($importe_restante > 0) {
                DB::rollBack();
                return ['error' => 'No hay denominaciones disponibles para entregar el monto exacto'];
            }

            DB::commit();

            return [
                'success' => true,
                'billetes' => $billetes_entregar,
                'total' => $importe
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Error durante el canje del cheque: ' . $e->getMessage()];
        }
    }
}
