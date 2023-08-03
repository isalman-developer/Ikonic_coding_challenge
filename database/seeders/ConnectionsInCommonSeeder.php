<?php

namespace Database\Seeders;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConnectionsInCommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

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
    }
}
