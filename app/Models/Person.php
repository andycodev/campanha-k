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

    public function setFullNameAttribute($value)
    {
        $this->attributes['full_name'] = mb_strtoupper($this->quitarTildes(trim($value)), 'UTF-8');
    }

    private function quitarTildes($cadena)
    {
        $buscar  = ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'];
        $reemplazar = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        return str_replace($buscar, $reemplazar, $cadena);
    }
}
