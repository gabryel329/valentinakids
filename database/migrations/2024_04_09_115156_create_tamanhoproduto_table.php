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
        Schema::table('tamanhoproduto', function (Blueprint $table) {
            $table->integer('produto_id');
            $table->integer('tamanho_id');

            $table->foreign('produto_id')->references('id')->on('produto')->onDelete('cascade');
            $table->foreign('tamanho_id')->references('id')->on('tamanho')->onDelete('cascade');

            $table->integer('quantidade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tamanhoproduto', function (Blueprint $table) {
            //
        });
    }
};
