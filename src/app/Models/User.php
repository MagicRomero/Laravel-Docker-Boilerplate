<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['created_at_human'];

    protected $perPage = 20;

    public static $rules = [
        'role_name' => 'required|exists:roles,name',
        'email' => 'required|email',
        'phone' => 'string|regex: /^[0-9]{3,15}+$/',
        'password' => 'required|string',
        'lang' => "string|in:es,en,it,fr,de,pt,pl,nl",
        'status' => 'string|in,ACTIVE,PENDING,BLOCKED'
    ];


    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
