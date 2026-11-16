<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\SoftDeletes;

class Guest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'first_name',
        'infix',
        'last_name',
        'email',
        'phone',
        'street',
        'house_number',
        'postal_code',
        'city',
        'country_code',
        'language_id',
    ];

    protected function name(): Attribute
    {
        return Attribute::get(fn() => collect([$this->first_name, $this->infix, $this->last_name])
            ->filter()
            ->implode(' '));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
