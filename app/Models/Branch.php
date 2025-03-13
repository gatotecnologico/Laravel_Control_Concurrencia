<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    public function tellers() {
        return $this -> hasOne(Teller::class);
    }

    public function getBranch($sucursal_id) {
        $sucursal = Branch::firstOrCreate(
            ['id' => $sucursal_id],
            ['abierta' => false]
        );
        return $sucursal;
    }
}
