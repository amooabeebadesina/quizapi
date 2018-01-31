<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'email', 'password', 'phone', 'last_name'
    ];

    protected $hidden = [
        'password', 'remember_token', 'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($roles)
    {
        return in_array($this->role->label, $roles) ? true : false ;
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
