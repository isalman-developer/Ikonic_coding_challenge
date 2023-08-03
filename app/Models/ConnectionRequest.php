<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectionRequest extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id', 'receiver_id'];

    // Connection request belongs to a sender (user).
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Connection request belongs to a receiver (user).
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
