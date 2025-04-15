<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    protected $fillable = [
        'padre_id',
        'nombre',
        'apellido',
        'fecha_nacimiento'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Padre::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }
}
