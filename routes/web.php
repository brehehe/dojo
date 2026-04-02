<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'posts' => \App\Models\Post::latest()->take(3)->get(),
        'galleries' => \App\Models\Gallery::latest()->take(6)->get(),
    ]);
})->name('home');
Route::get('/athlete/{athlete:nik}', function (\App\Models\Athlete $athlete) {
    return view('athlete-profile', ['athlete' => $athlete]);
})->name('athlete.detail');
Route::view('/piala_walikotasby2026', 'register')->name('register');
