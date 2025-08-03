<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

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
