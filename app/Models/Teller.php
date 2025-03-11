<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    private $denominaciones = [];
    protected $primaryKey = ['sucursal', 'denominacion'];
    public $incrementing = false;
    protected $fillable = ['sucursal', 'denominacion'];

    public function __construct() {
        $this->denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];
    }

    public function branch() {
        return $this -> belongsTo(Branch::class);
    }

    protected $guarded = [];

    public function abrirCaja($sucursal, $cajero) {
        $abierta = $sucursal->abierta;
        if($abierta === 0) {
            foreach ($this->denominaciones as $denominacion) {
                self::upsert([
                    'sucursal' => $sucursal->id,
                    'denominacion' => $denominacion,
                    'existencia' => rand(5, 20),
                    'entregados' => 0,
                ], ['sucursal', 'denominacion'], ['existencia', 'entregados']);
            }
            $sucursal->abierta = true;
            $sucursal->save();
        }
    }
}
