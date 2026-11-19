<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('profile.index')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Học bạ: Thêm - Tải xuống - Xóa
    Route::post('/transcripts', [ProfileController::class, 'storeTranscript'])->name('transcripts.store');
    Route::get('/transcripts/{id}/download', [ProfileController::class, 'downloadTranscript'])->name('transcripts.download');
    Route::delete('/transcripts/{id}', [ProfileController::class, 'deleteTranscript'])->name('transcripts.destroy');

    // Học vấn: Thêm - Sửa - Xóa
    Route::post('/education', [ProfileController::class, 'storeEducation'])->name('education.store');
    Route::put('/education/{id}', [ProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{id}', [ProfileController::class, 'deleteEducation'])->name('education.destroy');

    // Kỹ năng: Thêm - Sửa - Xóa
    Route::post('/skills', [ProfileController::class, 'storeSkill'])->name('skills.store');
    Route::put('/skills/{id}', [ProfileController::class, 'updateSkill'])->name('skills.update');
    Route::delete('/skills/{id}', [ProfileController::class, 'deleteSkill'])->name('skills.destroy');

    // Sở thích: Thêm - Sửa - Xóa
    Route::post('/hobbies', [ProfileController::class, 'storeHobby'])->name('hobbies.store');
    Route::put('/hobbies/{id}', [ProfileController::class, 'updateHobby'])->name('hobbies.update');
    Route::delete('/hobbies/{id}', [ProfileController::class, 'deleteHobby'])->name('hobbies.destroy');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');
});