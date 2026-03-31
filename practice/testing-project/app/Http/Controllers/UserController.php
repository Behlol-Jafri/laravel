<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|numeric',
            ],
            [
                'name.required'=> 'Name is required!',
                'email.required'=> 'Email is required!',
                'email.email'=> 'Enter the correct email address.',
                'password.required'=> 'Password is required!',
            ]
        );


        $users = DB::table('users')
            ->insert([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
            ]);

        return $request->all();
    }
}
