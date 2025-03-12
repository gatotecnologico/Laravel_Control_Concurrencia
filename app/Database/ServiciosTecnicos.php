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

    public function agregarBilletes($sucursal_id, $denominacion, $existencia) {
        $actual = DB::table('tellers')->where('sucursal', $sucursal_id)->where('denominacion', $denominacion)->first();
        DB::table('tellers')->upsert([
            'sucursal' => $sucursal_id,
            'denominacion' => $denominacion,
            'existencia' =>($actual ? $actual->existencia : 0) + $existencia,
            'entregados' => $actual ? $actual->entregados : 0,
        ],['sucursal', 'denominacion'], ['existencia']);
    }
}
