<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Announcement;
use App\Models\Updates;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AntivpnController;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

Route::get('/otp', function (Request $request) {
    $email = $request->query('email');
    $username = $request->query('username');

    if (!$email || !$username) {
        return response()->json([
            'success' => false,
            'message' => 'Email and username are required'
        ], 400);
    }

    // Find the user by email and username
    $user = User::where('email', $email)
                ->where('username', $username)
                ->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in cache (optional)
    cache()->put('otp_'.$email, $otp, 300); // valid 5 minutes

    // Update user's 2FA code
    $user->twomfa_code = $otp;
    $user->save();

    // Send email
    try {
        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your One-Time Password (OTP)');
        });

        return response("1", 200)->header('Content-Type', 'text/plain');
    } catch (\Exception $e) {
        return response("0", 500)->header('Content-Type', 'text/plain');
    }
});