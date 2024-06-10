<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('pages.display');
// });

Route::get('/', function () {
    $messages = ['Welcome to our website!', 'Latest news: Laravel 11 is released!', 'Check out our new features!'];
    return view('pages.display', compact('messages'));
});