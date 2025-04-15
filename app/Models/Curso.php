<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $fillable = [
        'academia_id',
        'nombre',
        'descripcion',
        'costo',
        'duracion',
        'modalidad'
    ];

    public function academia(): BelongsTo
    {
        return $this->belongsTo(Academia::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function comunicaciones(): HasMany
    {
        return $this->hasMany(Comunicacion::class);
    }
}
