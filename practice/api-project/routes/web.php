<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});


Route::view('addPost','addPost');

Route::view('viewPost/{id}','viewPost');

Route::view('updatePost/{id}','updatePost');

Route::view('post','postTable');
