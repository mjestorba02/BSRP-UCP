<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Announcement;
use App\Models\Updates;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\AntivpnController;

Route::get('/check/{ip}', [AntivpnController::class, 'checkIp']);

//Announcement
Route::post('/discord-announcement', function (Request $request) {
    try {
        $validated = $request->validate([
            'message' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $announcement = \App\Models\Announcement::create($validated);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Discord announcement error: '.$e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


Route::get('/get-latest-announcement', function () {
    return response()->json([
        'message' => Cache::get('latest_announcement', 'No announcement yet.'),
        'image_url' => Cache::get('latest_announcement_image')
    ]);
});

//Patch & Updates
Route::post('/discord-updates', function (Request $request) {
    try {
        $validated = $request->validate([
            'updates' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $update = \App\Models\Updates::create($validated);

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('Patch & Updates error: '.$e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


Route::get('/get-latest-updates', function () {
    return response()->json([
        'message' => Cache::get('latest_updates', 'No updates yet.'),
        'image_url' => Cache::get('latest_updates_image')
    ]);
});