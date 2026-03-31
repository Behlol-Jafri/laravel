<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@app.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create(['name' => 'Sara Ahmed',  'email' => 'sara@app.com',  'password' => Hash::make('password'), 'role' => 'user']);
        User::create(['name' => 'Omar Khalid', 'email' => 'omar@app.com',  'password' => Hash::make('password'), 'role' => 'user']);
        User::create(['name' => 'Fatima Noor', 'email' => 'fatima@app.com', 'password' => Hash::make('password'), 'role' => 'user']);
        User::create(['name' => 'Bilal Hassan', 'email' => 'bilal@app.com', 'password' => Hash::make('password'), 'role' => 'user']);
    }
}
