<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Academia extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'email'
    ];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }
}
