<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'Ahmed Sobhy',
            'timezone' => 'Africa/Cairo',
            'password' => Hash::make('test'),
        ])->assignRole('admin');

        // factory(User::class, 35)->create();
    }
}
