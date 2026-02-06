<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'general_needs'];

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class);
    }
}
