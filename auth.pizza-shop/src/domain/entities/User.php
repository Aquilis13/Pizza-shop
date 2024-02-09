<?php

namespace pizzashop\auth\api\domain\entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public const NOT_ACTIVE = 0;
    public const ACTIVE = 1;

    protected $connection = 'auth';
    protected $table = 'users';
    protected $keyType = 'string';
    protected $primaryKey = 'email';
    public $timestamps = false;
    protected $fillable = ['email', 'password', 'active','activation_token', 'activation_token_expiration_date', 'refresh_token', 'refresh_token_expiration_date', 'reset_passwd_token', 'reset_passwd_token_expiration_date', 'username'];
}