<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SucursalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branch = new Sucursal();
        $branch->name = 'Sucursal 1';
        $branch->save();
    }
}
