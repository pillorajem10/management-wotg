<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seeker extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'seekers';
    protected $primaryKey = 'id';
    public $timestamps = false; // Disable timestamps

    // Define the fillable attributes
    protected $fillable = [
        'seeker_fname',
        'seeker_lname',
        'seeker_nickname',
        'seeker_gender',
        'seeker_age',
        'seeker_email',
        'seeker_country',
        'seeker_city',
        'seeker_missionary',
        'seeker_status',
        'seeker_dgroup_leader',
        'seeker_catch_from',
    ];

    // Optionally, you can define any casts if needed
    protected $casts = [
        'seeker_age' => 'integer',
        'seeker_missionary' => 'string', // or 'boolean' if you prefer
        'seeker_dgroup_leader' => 'string', // or 'boolean' if you prefer
    ];
}
