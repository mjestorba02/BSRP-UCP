<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class ProfileController extends Controller
{
    public function stats() {
        $user = auth()->user();

        $gangs = DB::table('gangs')->get();
        $gangranks = DB::table('gangranks')->get();

        $factions = DB::table('factions')->get();
        $gfactionranks = DB::table('factionranks')->get();
        return view('dashboard', [
            'mainContent' => 'profile.profile',
            'user' => $user,
            'gangs' => $gangs,
            'gangranks' => $gangranks,
            'factions' => $factions,
            'factionranks' => $gfactionranks,
        ]);
    }

    public function inventory() {
        return view('profile.inventory');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:10', 'confirmed', 'regex:/[0-9]/'],
        ], [
            'new_password.regex' => 'The new password must contain at least one number.',
        ]);

        $user = Auth::user();
        $currentHash = strtoupper(hash('whirlpool', $request->current_password));

        if ($user->password !== $currentHash) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->password = strtoupper(hash('whirlpool', $request->new_password));
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function punishRecords() {
        // Example: fetch from a 'punishments' table
        $records = auth()->user()->punishments ?? [];
        return view('profile.punish-records', compact('records'));
    }
}
