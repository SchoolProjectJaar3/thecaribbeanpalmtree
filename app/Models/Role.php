<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['code', 'name', 'description'];

    public static function defaultRole(): self
    {
        return static::query()->firstOrCreate(
            ['code' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Volledige toegang tot de beheeromgeving',
            ],
        );
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
