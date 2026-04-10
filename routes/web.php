<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Auth\logout;
use App\Http\Controllers\Auth\Login;

Route::get('/', [ChirpController::class, 'index']);

Route::middleware('auth')->group(function() {
    Route::post('/chirps', [ChirpController::class, 'store']);
    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit']);
    Route::put('/chirps/{chirp}', [ChirpController::class, 'update']);
    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::patch('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name');
    Route::patch('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.email');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::post('/register', Register::class)->middleware('guest');
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', Login::class)->middleware('guest');
Route::post('/logout', Logout::class)->middleware('auth')->name('logout');