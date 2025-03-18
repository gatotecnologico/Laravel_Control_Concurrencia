<?php

namespace App\Database;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ServiciosTecnicos extends Model
{
    public function generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal)
    {
        try {
            DB::beginTransaction();
            DB::table('tellers')
                ->where('sucursal', $sucursal_id)
                ->lockForUpdate()->first();

            DB::table('tellers')->insert([
                'sucursal' => $sucursal_id,
                'denominacion' => $denominacion,
                'existencia' => $existencia,
                'entregados' => 0,
            ]);
            $sucursal->abierta = 1;
            $sucursal->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return 'Error, la transaccion falló';
        }
    }

    public function agregarBilletes($sucursal_id, $denominacion, $existencia)
    {
        try {
            DB::beginTransaction();
            $actual = DB::table('tellers')
                ->where('sucursal', $sucursal_id)
                ->where('denominacion', $denominacion)
                ->lockForUpdate()
                ->first();

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
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return 'Error, la transaccion falló';
        }
    }

    public function obtenerBilletes($sucursal_id)
    {
        $denominaciones = [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1];

        try {
            $billetes = DB::table('tellers')
                ->where('sucursal', $sucursal_id)
                ->whereIn('denominacion', $denominaciones)
                ->get();

            return ['success' => true, 'billetes' => $billetes];
        } catch (\Exception $e) {
            return ['error' => 'Error al obtener los billetes: ' . $e->getMessage()];
        }
    }
}
