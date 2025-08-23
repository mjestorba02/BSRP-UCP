<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Announcement;
use App\Models\Updates;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AntivpnController;
use Illuminate\Support\Facades\Mail;

Route::get('/otp', function (Request $request) {
    $email = $request->query('email');

    if (!$email) {
        return response()->json(['success' => false, 'message' => 'Email is required'], 400);
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);

    // Store OTP in cache or database for validation later
    cache()->put('otp_'.$email, $otp, 300); // valid 5 minutes

    // Send email
    try {
        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Your One-Time Password (OTP)');
        });

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});