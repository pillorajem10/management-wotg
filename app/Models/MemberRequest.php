<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberRequest extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow the default naming convention (plural of the model name)
    protected $table = 'member_requests';

    // Define the fillable properties (mass assignable)
    protected $fillable = [
        'user_id',
        'dgroup_leader_id',
        'status',
    ];

    // Define the timestamps for created_at and updated_at (enabled by default in Laravel)
    public $timestamps = true;

    // A member request belongs to a user (the member making the request)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Explicitly define the foreign key
    }

    // A member request belongs to a dgroup leader (who is also a user)
    public function dgroupLeader()
    {
        return $this->belongsTo(User::class, 'dgroup_leader_id'); // Explicitly define the foreign key
    }
}
