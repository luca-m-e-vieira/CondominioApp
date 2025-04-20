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
        Schema::create('sindico_condominio', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('condominio_id')->constrained();
            $table->boolean('ativo')->default(false);
            $table->timestamps();
            $table->primary(['user_id', 'condominio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sindico_condominio');
    }
};
