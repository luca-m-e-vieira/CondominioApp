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
        Schema::create('apartamentos', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->foreignId('condominio_id')
                  ->constrained('condominios')
                  ->onDelete('cascade'); // Referencia condominios
            $table->foreignId('morador_id')
                  ->nullable()
                  ->constrained('moradores')
                  ->onDelete('set null'); // Referencia moradores
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartamentos');
    }
};
