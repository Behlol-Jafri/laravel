<?php

namespace App\Http\Controllers;

// use App\Models\User;
use Illuminate\Support\Facades\DB;

// use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUsers(){
       $users = DB::table('users');
    //    return  $users;
    dd($users);
    }
}
