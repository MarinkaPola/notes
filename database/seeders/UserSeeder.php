<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        User::create([
            'name'=> 'Mari',
            'email'=> env('USER_EMAIL'),
            'password'=> bcrypt(env('USER_PASSWORD')),
        ]);
    }
}
