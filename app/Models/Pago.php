<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'matricula_id',
        'metodo',
        'monto',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'date'
    ];

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }
}
