<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['role_id', 'email', 'password', 'first_name', 'infix', 'last_name', 'phone', 'language_id'])]
#[Hidden(['password', 'two_factor_secret'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $rememberTokenName = '';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_secret' => 'encrypted',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => collect([$this->first_name, $this->infix, $this->last_name])
            ->filter()
            ->implode(' '));
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
