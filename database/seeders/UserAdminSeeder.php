<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pmb.test'],
            ['name' => 'Admin PMB','password'=>Hash::make('password123'),'role'=>'admin']
        );
        User::updateOrCreate(
            ['email' => 'staff@pmb.test'],
            ['name' => 'Staff PMB','password'=>Hash::make('password123'),'role'=>'staff']
        );
    }
}

