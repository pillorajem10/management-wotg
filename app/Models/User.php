<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id';
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'user_fname',
        'user_lname',
        'user_role',
        'user_gender',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // You can define other methods or relationships here
}
