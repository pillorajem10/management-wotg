<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $timestamps = true; // Enable timestamps

    protected $fillable = [
        'blog_title',
        'blog_body',
        'blog_thumbnail',
        'blog_creator',
        'blog_approved',
        'blog_is_hidden',            // Added is_hidden
        'blog_release_date_and_time' // Added release date and time
    ];    

    protected $casts = [
        'blog_creator' => 'integer',
        'blog_is_hidden' => 'boolean',  
        'blog_approved' => 'boolean',          // Cast to boolean
        'blog_release_date_and_time' => 'datetime', // Cast to datetime
    ];

    // Define the relationship to User
    public function creator()
    {
        return $this->belongsTo(User::class, 'blog_creator', 'id');
    }
}
