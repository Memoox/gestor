<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function prioridad()
    {
        return $this->belongsTo(Prioridad::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function estatus()
    {
        return $this->belongsTo(Estatus::class);
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class);
    }
    
    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
