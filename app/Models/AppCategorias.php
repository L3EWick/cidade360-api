<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCategorias extends Model
{
    use HasFactory;

    protected $table = 'appmobileoption';

    protected $fillable = [
        'option', 
    ];


    public function eventos()
    {
        return $this->hasMany(AppEvento::class, 'categoria_id', 'id');
    }
}