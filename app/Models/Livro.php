<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'autor_id',
        'data',
        'preco',
        'editora',
        'categoria',
    ];

    public function autor()
{
    return $this->belongsTo(Autor::class);
}
}
