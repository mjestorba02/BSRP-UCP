<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\GangController;

Route::get('/', [AuthController::class, 'showLoginForm'])->name('landing');


Route::get('/sendotp', function () {
    // You can load a view or redirect elsewhere
    return view('auth.otp');
});

Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/dashboard', [AuthController::class, 'showMainPage'])->name('dashboard');
});


Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Profile route
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', [ProfileController::class, 'stats'])->name('profile'); // <-- THIS fixes the error
    Route::get('stats', [ProfileController::class, 'stats'])->name('profile.stats');
    Route::get('inventory', [ProfileController::class, 'inventory'])->name('profile.inventory');
    Route::get('punish-records', [ProfileController::class, 'punishRecords'])->name('profile.punishRecords');
});

//Change Password
Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');

//Fetching Ban History
Route::get('/ban-history/{username}', [ProfileController::class, 'getBanHistory']);

//Misc
Route::prefix('misc')->middleware(['auth'])->group(function () {
    Route::get('/phonebook', [MiscController::class, 'phoneBook'])->name('misc.phonebook');
    Route::get('/turfs', [MiscController::class, 'turfs'])->name('turfs');
    Route::get('/toys', [MiscController::class, 'toys'])->name('toys');
});

//Delete for Announcements & Updates
Route::delete('/announcements/{id}', [AuthController::class, 'destroy_announcement'])->name('announcements.destroy');
Route::delete('/updates/{id}', [AuthController::class, 'destroy_updates'])->name('updates.destroy');

// Settings route
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

// Logout (must be POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//For Successfully registered users
Route::get('/success', function () {
    return view('auth.success');
})->name('auth.success');

//Gang Management
Route::middleware('auth')->group(function () {
    Route::get('/gang/manage', [GangController::class, 'index'])->name('gang.manage');
    Route::post('/gang/invite', [GangController::class, 'invite'])->name('gang.invite');
    Route::delete('/gang/kick/{id}', [GangController::class, 'kick'])->name('gang.kick');
    Route::post('/gang/update-rank/{uid}', [GangController::class, 'updateRank'])->name('gang.updateRank');
});
