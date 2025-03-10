<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    private $denominaciones;

    public function __construct() {
        $this->denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];
    }

    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function abrirCaja($cajero) {
        $abierta = filter_var(request('abierta'), FILTER_VALIDATE_BOOLEAN);

        if($abierta === true) {
            return;
        }

        foreach ($this->denominaciones as $denominacion) {
            self::create([
                'sucursal_id' => $cajero->sucursal_id,
                'denominacion' => $cajero->denominacion = $denominacion,
                'existencia' => $cajero->existencia = rand(5, 20),
                'entregados' => $cajero->entregados = 0,
                'abierta' => $cajero->abierta = true,
            ]);
            $cajero->save();
        }

    return dd(Teller::all());
    }
}
