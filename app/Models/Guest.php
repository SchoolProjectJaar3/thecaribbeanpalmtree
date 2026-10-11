<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'first_name', 'infix', 'last_name', 'email', 'phone',
    'street', 'house_number', 'postal_code', 'city', 'country_code', 'language_id',
])]
class Guest extends Model
{
    use SoftDeletes;

    protected function name(): Attribute
    {
        return Attribute::get(fn () => collect([$this->first_name, $this->infix, $this->last_name])
            ->filter()
            ->implode(' '));
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
