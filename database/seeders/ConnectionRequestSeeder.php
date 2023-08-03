<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\ConnectionRequest;

class ConnectionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate connection requests from random users to other users
        $users = User::all();

        foreach ($users as $user) {
            // Generate a random number of connection requests for each user i.e 6
            $connectionRequestCount = rand(0, 20);

            // Get a random list of users to whom connection requests will be sent taken a collection of any random 6 users object
            $randomUsers = $users->random($connectionRequestCount);

            // iterating over those 6 random users collection
            foreach ($randomUsers as $randomUser) {
                if ($user->id !== $randomUser->id) {
                    ConnectionRequest::create([
                        'sender_id' => $user->id,
                        'receiver_id' => $randomUser->id,
                    ]);
                }
            }
        }
    }
}
