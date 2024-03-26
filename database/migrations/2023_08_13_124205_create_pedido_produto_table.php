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
        Schema::create('pedido_produto', function (Blueprint $table) {
            $table->integer('pedido_id');
            $table->integer('produto_id');
            $table->integer('quantidade');
            $table->timestamps();

            $table->foreign('pedido_id')
                  ->references('id')->on('pedido')
                  ->onDelete('cascade');

            $table->foreign('produto_id')
                  ->references('id')->on('produto')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_produto');
    }
};
