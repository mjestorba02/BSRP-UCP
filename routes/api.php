<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

Route::post('/discord-announcement', function (Request $request) {
    $validated = $request->validate([
        'message' => 'required|string',
        'image_url' => 'nullable|url',
    ]);

    $announcement = Announcement::create($validated);

    return response()->json(['success' => true]);
});

Route::get('/get-latest-announcement', function () {
    return response()->json([
        'announcement' => Cache::get('latest_announcement', 'No announcement yet.'),
        'image_url' => Cache::get('latest_announcement_image')
    ]);
});
