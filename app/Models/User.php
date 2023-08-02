<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // User has many connection requests where they are the sender.
    public function sentRequests()
    {
        // request which are sent and not accepted yet
        return $this->hasMany(ConnectionRequest::class, 'sender_id', 'id')->where('is_accepted', false);
    }

    // User has many connection requests where they are the receiver.
    public function receivedRequests()
    {
        return $this->hasMany(ConnectionRequest::class, 'receiver_id', 'id');
    }

    // User has many connections (a many-to-many relationship with the User model).
    public function connections()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connected_user_id');
    }

    public function commonConnections(User $user)
    {
        return $this->connections()->whereHas('connections', function ($query) use ($user) {
            $query->where('connected_user_id', $user->id);
        });
    }

    public function suggestions()
    {
        $user = $this;
        return $user->whereDoesntHave('connections')
            ->whereDoesntHave('sentRequests', function ($query) use ($user) {
                $query->where('receiver_id', $user->id);
            })
            ->whereDoesntHave('receivedRequests', function ($query) use ($user) {
                $query->where('sender_id', $user->id);
            })
            ->where('id', '!=', $user->id);
    }
}
