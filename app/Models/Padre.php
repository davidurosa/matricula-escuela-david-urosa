<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padre extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono'
    ];

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class);
    }

    public function comunicaciones(): HasMany
    {
        return $this->hasMany(Comunicacion::class);
    }
}
