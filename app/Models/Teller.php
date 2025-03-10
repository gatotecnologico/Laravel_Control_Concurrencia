<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    private $denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];
    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function abrirCaja($cajero) {
        $abierta = filter_var(request('abierta'), FILTER_VALIDATE_BOOLEAN);

        if($abierta === true) {
            return;
        }

        foreach (self::$denominaciones as $denominacion) {
            self::create([
                'id_sucursal' => $cajero->id_sucursal,
                'denominacion' => $denominacion,
                'existencia' => rand(5, 20),
                'entregados' => 0,
                'abierta' => true,
                $cajero->save(),
            ]);
        }

        return dd(Teller::all());
    }
}
