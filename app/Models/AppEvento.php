<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppEvento extends Model
{
    use HasFactory;

    protected $table = 'app_eventos';

    protected $fillable = [
        'lat',
        'long',
        'cidade',
        'bairro',
        'estado',
        'categoria',
        'imagem',
    ];

    public function categoria()
{
    return $this->belongsTo(AppCategoria::class, 'categoria_id', 'id');
}
}