<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    private $denominaciones = [];
    protected $primaryKey = ['sucursal', 'denominacion'];
    public $incrementing = false;
    protected $fillable = ['sucursal', 'denominacion'];

    public function __construct()
    {
        $this->denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected $guarded = [];

    public function abrirCaja($sucursal, $cajero)
    {
        $abierta = filter_var(request('abierta'), FILTER_VALIDATE_BOOLEAN);

        if ($abierta === true) {
            return;
        }
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

    public function agregarBilletes($sucursal)
    {
        foreach ($this->denominaciones as $denominacion) {
            $actual = self::where('sucursal', $sucursal->id)
                ->where('denominacion', $denominacion)
                ->first();

            self::upsert([
                'sucursal' => $sucursal->id,
                'denominacion' => $denominacion,
                'existencia' => ($actual ? $actual->existencia : 0) + rand(5, 20),
                'entregados' => $actual ? $actual->entregados : 0,
            ], ['sucursal', 'denominacion'], ['existencia']);
        }
    }
}
