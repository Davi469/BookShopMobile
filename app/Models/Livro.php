<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livro extends Model
{
     use HasFactory;

    protected $fillable = [
        'nome',
        'autor',
        'data_publicacao',
        'preco',
        'editora',
        'categoria',
    ];
}
