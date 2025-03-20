<?php

namespace App\Database;

use Exception;
use Illuminate\Support\Facades\DB;

class ServiciosTecnicos
{
    public function generarExistencias($sucursal_id, $denominacion, $existencia, $sucursal)
    {
        try {
            DB::beginTransaction();
            DB::table('cajas')
                ->where('sucursal', $sucursal_id)
                ->lockForUpdate()->first();

            DB::table('cajas')->insert([
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
            $actual = DB::table('cajas')
                ->where('sucursal', $sucursal_id)
                ->lockForUpdate()
                ->first();

            if ($actual) {
                DB::table('cajas')->where('sucursal', $sucursal_id)->where('denominacion', $denominacion)
                    ->update([
                        'existencia' => DB::raw("existencia + $existencia"),
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
            DB::beginTransaction();
            $billetes = DB::table('cajas')
                ->where('sucursal', $sucursal_id)
                ->whereIn('denominacion', $denominaciones)
                ->lockForUpdate()
                ->get();

            DB::commit();
            return ['success' => true, 'billetes' => $billetes];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['error' => 'Error al obtener los billetes: ' . $e->getMessage()];
        }
    }

    public function getBeginTran() {
        return DB::beginTransaction();
    }

    public function getRollback() {
        return DB::rollBack();
    }

    public function getCommit() {
        DB::commit();
    }

    public function actualizarBilletes($sucursal, $denominacion, $cantidad) {
        DB::table('cajas')
        ->where('sucursal', $sucursal->id)
        ->where('denominacion', $denominacion)
        ->decrement('existencia', $cantidad, ['entregados' => DB::raw("entregados + $cantidad")]);
    }

    public function bloquearCaja($sucursal) {
        DB::table('cajas')
        ->where('sucursal', $sucursal->id)
        ->lockForUpdate()
        ->get();
    }
}
