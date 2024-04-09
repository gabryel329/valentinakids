<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tamanho extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tamanho';
    protected $fillable = ['nome'];
    protected $dates = ['deleted_at'];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'tamanhoproduto', 'produto_id', 'tamanho_id')
                    ->withPivot('quantidade')
                    ->withTimestamps();
    }
}
