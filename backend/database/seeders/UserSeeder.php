<?php

namespace Database\Seeders;

// use App\Models\Student;
use App\Models\User;
// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 1; $i <= 10; $i++) {

        //     User::create([
        //         "name" => fake()->name(),
        //         "email" => fake()->unique()->email(),
        //         "password" => fake()->password(),
        //     ]);
        // }


        $json = File::get('database/json/users.json');
        $students = collect(json_decode($json));
        $students->each(function ($student) {
            User::create([
                "name" => $student->name,
                "email" => $student->email,
                "password" => $student->password,
            ]);
        });
    }
}
