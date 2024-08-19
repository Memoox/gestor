<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    public function usuario()
    {
        return $this->hasOne(User::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
