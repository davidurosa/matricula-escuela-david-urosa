<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comunicacion extends Model
{
    protected $table = 'comunicaciones';

    protected $fillable = [
        'titulo',
        'mensaje',
        'fecha_envio',
        'curso_id',
        'padre_id'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime'
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Padre::class);
    }
}
