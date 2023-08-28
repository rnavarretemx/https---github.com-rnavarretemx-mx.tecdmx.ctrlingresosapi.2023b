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
        Schema::create('ingresos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora_agendada');
            $table->time('hora_entrada')->nullable();
            $table->time('hora_salida')->nullable();
            $table->float('edo_cita');
            $table->longText('codigo');
            $table->longText('codigo_qr');
            $table->foreignId('personal_id')->references('id')->on('personal')->onDelete('cascade');
            $table->foreignId('visitante_id')->references('id')->on('visitantes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresos');
    }
};
