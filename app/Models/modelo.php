namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $table = 'caja';
    public $timestamps = false;
    protected $primaryKey = ['id_sucursal', 'denominacion'];
    public $incrementing = false;

    protected $fillable = ['id_sucursal', 'denominacion', 'existencia', 'entregados'];

    private static $denominaciones = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000];

    public static function abrirCaja($id_sucursal)
    {
        if (self::where('id_sucursal', $id_sucursal)->exists()) {
            return false;
        }

        foreach (self::$denominaciones as $denominacion) {
            self::create([
                'id_sucursal' => $id_sucursal,
                'denominacion' => $denominacion,
                'existencia' => rand(5, 20),
                'entregados' => 0
            ]);
        }
        return true;
    }

    public static function agregarBilletes($id_sucursal)
    {
        foreach (self::$denominaciones as $denominacion) {
            $caja = self::where('id_sucursal', $id_sucursal)->where('denominacion', $denominacion)->first();
            if ($caja) {
                $caja->existencia += rand(5, 15);
                $caja->save();
            }
        }
    }

    public static function canjearCheque($id_sucursal, $monto)
    {
        $billetesDisponibles = self::where('id_sucursal', $id_sucursal)
            ->where('existencia', '>', 0)
            ->orderByDesc('denominacion')
            ->get();

        $montoRestante = $monto;
        $billetesEntregados = [];

        foreach ($billetesDisponibles as $billete) {
            if ($montoRestante <= 0) break;

            $cantidadNecesaria = intdiv($montoRestante, $billete->denominacion);
            $cantidadADar = min($cantidadNecesaria, $billete->existencia);

            if ($cantidadADar > 0) {
                $montoRestante -= ($cantidadADar * $billete->denominacion);
                $billetesEntregados[$billete->denominacion] = $cantidadADar;
            }
        }

        if ($montoRestante != 0) {
            return false;
        }

        foreach ($billetesDisponibles as $billete) {
            if (isset($billetesEntregados[$billete->denominacion])) {
                $billete->existencia -= $billetesEntregados[$billete->denominacion];
                $billete->entregados += $billetesEntregados[$billete->denominacion];
                $billete->save();
            }
        }

        return $billetesEntregados;
    }
}
