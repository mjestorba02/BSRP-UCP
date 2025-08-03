<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

Route::post('/discord-announcement', function (Request $request) {
    $announcement = $request->input('announcement');
    Cache::put('latest_announcement', $announcement);

    return response()->json(['message' => 'Announcement received']);
});

Route::get('/get-latest-announcement', function () {
    return response()->json([
        'announcement' => Cache::get('latest_announcement', 'No announcement yet.')
    ]);
});
