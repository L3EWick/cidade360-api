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
        Schema::create('app_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('lat'); 
            $table->string('long'); 
            $table->string('cidade'); 
            $table->string('bairro'); 
            $table->string('estado'); 
            $table->unsignedBigInteger('categoria_id'); 
            $table->string('imagem'); 
            $table->timestamps(); 

            
            $table->foreign('categoria_id')->references('id')->on('app_categorias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_eventos');
    }
};