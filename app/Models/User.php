<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_fname',
        'user_lname',
        'user_nickname',
        'user_role',
        'refresh_token',
        'email',
        'verification_token',
        'password',
        'user_gender',
        'user_mobile_number',
        'user_church_name',
        'user_birthday',
        'user_country',
        'user_city',
        'user_dgroup_leader',
        'approval_token',
        'user_ministry',
        'user_already_a_dgroup_leader',
        'user_already_a_dgroup_member',
        'user_profile_picture',
        'user_meeting_day',
        'user_meeting_time',
        'user_profile_banner',
        'reset_password_token',
        'reset_password_expires',
    ];

    protected $hidden = [
        'password',
        'refresh_token',
        'verification_token',
        'reset_password_token',
        'reset_password_expires',
    ];

    // ✅ Automatically hash passwords before saving
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Relationship with Seekers
     */
    public function seekers()
    {
        return $this->hasMany(Seeker::class, 'seeker_missionary', 'id');
    }

    public function dgroupLeader()
    {
        return $this->belongsTo(User::class, 'user_dgroup_leader');
    }
}
