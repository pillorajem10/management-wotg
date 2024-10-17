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
    ];

    protected $casts = [
        'blog_creator' => 'integer',
    ];

    // Define the relationship to User
    public function creator()
    {
        return $this->belongsTo(User::class, 'blog_creator', 'id');
    }
}

