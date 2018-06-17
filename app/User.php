<?php

namespace Parking;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/** @codeCoverageIgnore */
class User extends Authenticatable {
    use Notifiable;

    const ROLE_USER = 'user';
    const ROLE_BOT = 'bot';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isRole(string $role): bool {
        return strtolower(trim($role)) === $this->role;
    }
}
