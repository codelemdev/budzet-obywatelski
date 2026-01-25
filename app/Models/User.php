<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => Role::class,
    ];

    public function ideas(): HasMany
    {
        return $this->hasMany(Idea::class);
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'votes');
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    public function getAvatar(): string
    {
        return 'https://i.pravatar.cc/150?u='.md5($this->email);
    }
}
