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
        Schema::create('produto', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 150);
            $table->string('preco', 100);
            $table->string('descricao', 250)->nullable();
            $table->string('imagem', 150)->nullable();
            $table->integer('tipo')->nullable();
            $table->foreign('tipo')->references('id')->on('tipo_prod')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
