<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    protected $fillable = ['sector_id', 'name', 'family_needs'];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
