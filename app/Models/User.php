<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'banned_at' => 'datetime',
        'password' => 'hashed',
    ];

    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const SUPPLIER = 'supplier';

    public const CUSTOMER = 'customer';
    public const USER_TYPES = [self::CUSTOMER, self::SUPPLIER, self::ADMIN, self::SUPER_ADMIN];

    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('banned_at')
            ->where(fn($q) => $q->whereNotNull('email_verified_at')->orWhereNotNull('phone_verified_at'));
    }

    public function isVerified($key): bool
    {
        $key = $key . '_verified_at';
        return array_key_exists($key, $this->attributes) && !is_null($this->{$key});
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'supplier_id');
    }
}
