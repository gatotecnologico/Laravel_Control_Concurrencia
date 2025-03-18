<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $guarded = [];

    public function cajas() {
        return $this -> hasOne(Caja::class);
    }

    public function getSucursal($sucursal_id) {
        $sucursal = Sucursal::firstOrCreate(
            ['id' => $sucursal_id],
            ['abierta' => false]
        );
        return $sucursal;
    }
}
