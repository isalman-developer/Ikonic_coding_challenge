<?php

namespace Database\Seeders;

use App\Models\Connection;
use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Salman',
            'email' => 'salman@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'), // password
            'remember_token' => Str::random(10),
        ]);

        // Generate 50 sample users using the User factory
        User::factory()->count(50)->create();

        // Generate connection requests from random users to other users
        $users = User::all();

        foreach ($users as $user) {
            // Generate a random number of connection requests for each user
            $connectionRequestCount = rand(0, 20);

            // Get a random list of users to whom connection requests will be sent
            $randomUsers = $users->random($connectionRequestCount);

            foreach ($randomUsers as $randomUser) {
                if ($user->id !== $randomUser->id) {
                    ConnectionRequest::create([
                        'sender_id' => $user->id,
                        'receiver_id' => $randomUser->id,
                    ]);
                }
            }
        }

        // Generate connections in common with users
        foreach ($users as $user) {
            // Get the connections of the user
            $userConnections = $user->connections()->pluck('connected_user_id');

            // Get the common connections of the user's connections
            $commonConnections = $users->whereIn('id', $userConnections)
                ->where('id', '!=', $user->id)
                ->pluck('id');

            // Create connections in common
            foreach ($commonConnections as $commonConnection) {
                Connection::create([
                    'user_id' => $user->id,
                    'connected_user_id' => $commonConnection,
                ]);
            }
        }

        // User::factory()->count(50)->create();
    }
}
