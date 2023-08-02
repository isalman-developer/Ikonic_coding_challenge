<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'connected_user_id'];

    // sender user's connections, connection sent by a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Connection belongs to a connected user (receiver).
    public function connectedUser()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }
}
