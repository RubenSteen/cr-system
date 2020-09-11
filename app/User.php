<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'email', 'password', 'last_password_change', 'date_of_birth', 'street_name', 'house_number', 'zip_code', 'city', 'province', 'phone_number', 'mobile_number', 'comment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'active' => 'boolean',
        'admin' => 'boolean',
        'last_password_change' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public static function boot()
    {
        parent::boot();

        User::observe(Observers\UserObserver::class);
    }

    public function isActive()
    {
        return $this->active;
    }

    public function isAdmin()
    {
        return $this->admin;
    }

    public function fullName()
    {
        $string = "";

        if (!is_null($this->first_name)) {
            $string .= "{$this->first_name} ";
        }

        return $string .= $this->last_name;
    }
}
