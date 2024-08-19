<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'cat_categoria';

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
