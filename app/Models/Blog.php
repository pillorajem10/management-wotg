<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    public $timestamps = true; 

    protected $fillable = [
        'blog_title',
        'blog_body',
        'blog_thumbnail',
        'blog_creator',
        'blog_approved',
        'blog_release_date_and_time',
        'blog_intro',
        'blog_video',
        'blog_uploaded_by',
    ];      

    protected $casts = [
        'blog_creator' => 'integer',
        'blog_uploaded_by' => 'integer',
        'blog_approved' => 'boolean',          
        'blog_release_date_and_time' => 'datetime', 
    ];

    // Define the relationship to User
    public function creator()
    {
        return $this->belongsTo(User::class, 'blog_creator', 'id');
    }

    // Define the relationship to User for uploaded_by
    public function uploader()
    {
        return $this->belongsTo(User::class, 'blog_uploaded_by', 'id');
    }
}
