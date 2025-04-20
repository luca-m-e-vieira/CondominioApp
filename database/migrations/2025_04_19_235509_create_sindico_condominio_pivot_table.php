<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('sindico_condominio')) {
            Schema::create('sindico_condominio', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('condominio_id')->constrained()->cascadeOnDelete();
                $table->boolean('ativo')->default(false);
                $table->timestamps();
                
                $table->unique(['user_id', 'condominio_id']);
            });
        } else {
            
            Schema::table('sindico_condominio', function (Blueprint $table) {
                if (!Schema::hasColumn('sindico_condominio', 'ativo')) {
                    $table->boolean('ativo')->default(false)->after('condominio_id');
                }
            });
        }
    }
    
    public function down()
    {
        
        Schema::table('sindico_condominio', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
};