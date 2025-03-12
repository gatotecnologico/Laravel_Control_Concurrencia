<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    public function index()
    {
        $sucursal = Branch::find(1);
        return view('index', ['sucursal'=>$sucursal]);
    }
}
