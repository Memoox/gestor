<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'cat_tipo_doc';

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}