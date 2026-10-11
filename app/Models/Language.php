<?php

namespace App\Models;

use illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'is_default',
        'is_active',
        'sort_order'
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
