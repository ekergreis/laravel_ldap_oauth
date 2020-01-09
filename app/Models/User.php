<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password', 'objectguid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // [LDAP / OAUTH] Nécessaire pour générer token OAuth en fonction username au lieu de email
    public function findForPassport($username) {
        return $this->where('username', $username)->first();
    }
    // [LDAP / OAUTH] Nécessaire pour générer token OAuth sans vérifier password (vérifié sur LDAP)
    public function validateForPassportPasswordGrant($password) {
        return true;
    }
}
