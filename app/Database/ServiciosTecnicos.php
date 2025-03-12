<?php

namespace App\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ServiciosTecnicos extends Model
{
    public function generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal)
    {
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
        if ($actual) {
            DB::table('tellers')->where('sucursal', $sucursal_id)->where('denominacion', $denominacion)
                ->update([
                    'existencia' => $actual->existencia + $existencia,
                    'entregados' => $actual->entregados,
                ]);
        } else {
            DB::table('tellers')->insert([
                'sucursal' => $sucursal_id,
                'denominacion' => $denominacion,
                'existencia' => $existencia,
                'entregados' => 0,
            ]);
        }
    }

    public function cambiarCheque($sucursal_id, $importe)
    {
        if ($importe <= 0) {
            return ['error' => 'El importe debe ser mayor a cero'];
        }

        $denominaciones = [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1];
        $billetes_entregar = [];
        $importe_restante = $importe;

        $billetes = DB::table('tellers')
            ->where('sucursal', $sucursal_id)
            ->whereIn('denominacion', $denominaciones)
            ->get()
            ->keyBy('denominacion');

            DB::beginTransaction();
            try {
                foreach ($denominaciones as $denominacion) {
                    if (empty($billetes[$denominacion]) || $billetes[$denominacion]->existencia <= 0) {
                        continue;
                    }

                    $cantidad = min(floor($importe_restante / $denominacion), $billetes[$denominacion]->existencia);

                    if ($cantidad > 0) {
                        $billetes_entregar[$denominacion] = $cantidad;
                        $importe_restante -= $denominacion * $cantidad;

                        DB::table('tellers')
                            ->where('sucursal', $sucursal_id)
                            ->where('denominacion', $denominacion)
                            ->decrement('existencia', $cantidad, ['entregados' => DB::raw("entregados + $cantidad")]);
                    }
                }

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
