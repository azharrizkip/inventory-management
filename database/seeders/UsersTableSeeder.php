<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [];

        for ($i = 1; $i <= 10; $i++) {
            $userType = $i <= 5 ? 'admin' : 'superadmin';
            $username = $userType . $i;
            $now = Carbon::now();

            $users[] = [
                'user_type' => $userType,
                'username' => $username,
                'password' => Hash::make('password123'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('users')->insert($users);
    }
}
