<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class tipoProd extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_prod';
    protected $fillable = ['tipo'];
    protected $dates = ['deleted_at'];

    public function produto()
    {
        return $this->hasMany(Produto::class, 'tipo');
    }
}
