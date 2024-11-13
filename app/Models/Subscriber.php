<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'subscribers';

    // Define the attributes that are mass assignable
    protected $fillable = [
        'sub_fname',
        'sub_lname',
        'sub_email',
        'sub_gender',
        'sub_age',
        'sub_country',
        'sub_city',
    ];

    // If you want to disable the timestamps
    public $timestamps = false;
}
