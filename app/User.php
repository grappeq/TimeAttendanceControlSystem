<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use YoHang88\LetterAvatar\LetterAvatar;

class User extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatar($size = 160) {
        return new LetterAvatar($this->name, 'circle', $size);
    }

}
