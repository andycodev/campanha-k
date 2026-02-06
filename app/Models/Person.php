<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    // Laravel por defecto busca la tabla "people", así que está bien.
    protected $fillable = [
        'family_id',
        'full_name',
        'dni',
        'disposition',
        'personal_request'
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }
}
