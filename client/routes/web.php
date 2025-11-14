<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Page.Auth.Login');
});

Route::get('/register', function () {
    return view('Page.Auth.Register');
})->name('register');

Route::get('/login', function () {
    return view('Page.Auth.Login');
})->name('login');
