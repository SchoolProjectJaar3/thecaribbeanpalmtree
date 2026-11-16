<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    /**
     * De tabel seasons heeft geen created_at en updated_at.
     */
    public $timestamps = false;

    protected $fillable = [
        'name',
        'color',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
