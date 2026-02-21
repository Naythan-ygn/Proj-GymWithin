<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_login_at',
        'status'
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    // This ensures "status" always has a value even if null in DB
    public function getStatusAttribute($value)
    {
        return $value ?? 'active';
    }

    // Logic to check for the 30-day inactivity you mentioned
    public function checkInactivity()
    {
        if (in_array($this->role, ['admin', 'guest']))
            return;

        if ($this->last_login_at && $this->last_login_at->diffInDays(now()) > 30) {
            $this->update(['status' => 'inactive']);
        }
    }

    public function updateStatus()
    {
        // Admin roles are immune to auto-inactivity
        if (in_array($this->role, ['admin'])) {
            return;
        }

        // If last login was more than 30 days ago, set to inactive
        if ($this->last_login_at && $this->last_login_at->diffInDays(now()) > 30) {
            $this->status = 'inactive';
            $this->save();
        }
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'user';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

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
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
