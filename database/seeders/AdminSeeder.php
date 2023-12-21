<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userProperties = [
            'displayName' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'emailVerified' => true,
        ];
        app('firebase.auth')->createUser($userProperties);
        $user = app('firebase.auth')->getUserByEmail('admin@admin.com');
        app('firebase.auth')->setCustomUserClaims($user->uid, ['admin' => true]);
    }
}
