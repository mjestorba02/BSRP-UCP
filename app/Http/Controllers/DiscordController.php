<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Socialite;

class DiscordController extends Controller
{
    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function handleDiscordCallback()
    {
        $discordUser = Socialite::driver('discord')->user();

        // Save Discord ID to logged-in SA-MP account
        $user = Auth::user();
        $user->discord_id = $discordUser->id;
        $user->save();

        return redirect('/dashboard')->with('success', 'Discord linked successfully!');
    }
}
