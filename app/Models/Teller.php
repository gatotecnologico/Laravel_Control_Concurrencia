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
}
