<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = ['sender_id', 'receiver_id'];

    // sender user
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    //receiver user
    public function connection()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
