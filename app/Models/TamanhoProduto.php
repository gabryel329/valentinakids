<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TamanhoProduto extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tamanhoproduto';
    protected $fillable = ['produto_id', 'tamanho_id', 'quantidade'];
    protected $dates = ['deleted_at'];

    // Definir a chave primária personalizada
    protected $primaryKey = ['produto_id', 'tamanho_id'];

    // Indicar que as chaves primárias não são incrementais
    public $incrementing = false;

    // Desativar timestamps se não estiverem presentes na tabela
    public $timestamps = false;

    // Se desejar, você pode definir as chaves estrangeiras explicitamente
    // protected $foreignKey = 'produto_id';
    // protected $otherKey = 'tamanho_id';
}
