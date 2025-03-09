<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sucursal_id');
            $table->int('denominacion');
            $table->int('entregados');
            $table->int('existencia');

            $table->timestamps();
            $table->index('sucursal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tellers');
    }
};
