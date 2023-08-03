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
        // all users from the "users" table
        $users = User::all();

        // creating connections between the first 25 users and the next 25 users
        for ($i = 0; $i < 25; $i++) {
            for ($j = 25; $j < 50; $j++) {
                // dont create connections between the same user
                if ($i != $j) {
                    Connection::create([
                        "user_id" => $users[$i]->id, //$i user is the send
                        "connected_user_id" => $users[$j]->id //$j user is the receiver
                    ]);
                }
            }
        }

        // creating additional connections between 20 and 40 users and 40 to 50 users
        for ($i = 20; $i <= 45; $i++) {
            for ($j = 35; $j <= 50; $j++) {
                // dont create connections between the same user
                if ($i != $j) {
                    Connection::create([
                        "user_id" => $users[$i]->id, //$i user is the send
                        "connected_user_id" => $users[$j]->id //$j user is the receiver
                    ]);
                }
            }
        }
    }
}
