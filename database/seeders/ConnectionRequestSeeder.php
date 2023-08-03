<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\ConnectionRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
    }
}
