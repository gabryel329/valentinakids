<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'empresas';
    protected $fillable = ['nome', 'imagem', 'instagram', 'email', 'telefone', 'dias', 'horario'];
    protected $dates = ['deleted_at'];

}
