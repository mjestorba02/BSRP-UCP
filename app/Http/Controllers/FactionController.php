<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Faction;
use App\Models\User;

class FactionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get the user's faction
        $faction = Faction::find($user->faction);

        // Fetch faction members (users in the same faction)
        $members = User::where('faction', $faction->id)->orderByDesc('factionrank')->get();

        // Fetch ranks belonging to the faction
        $ranks = DB::table('factionranks')->where('id', $faction->id)->get();

        $factionranks = DB::table('factionranks')->get();

        return view('dashboard', [
            'mainContent' => 'faction.members',
            'faction' => $faction,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'factionranks' => $factionranks,
        ]);
    }

    public function kick($id)
    {
        $users = auth()->user();

        $highestRank = DB::table('factionranks')
            ->where('id', $users->faction)
            ->max('rank');

        if ($users->factionrank != $highestRank) {
            return back()->with('error', 'Action denied. You are no longer the faction leader.');
        }

        $user = User::findOrFail($id);

        if($users->faction !== $user->faction) {
            return back()->with('error', 'Action denied. That player is not longer your faction member.');
        }

        $user->faction = -1;
        $user->factionrank = 0;
        $user->walkietalkievcc1 = 0;
        $user->division = 0;
        $user->weapon_0 = 0;
        $user->weapon_1 = 0;
        $user->weapon_2 = 0;
        $user->weapon_3 = 0;
        $user->weapon_4 = 0;
        $user->weapon_5 = 0;
        $user->weapon_6 = 0;
        $user->weapon_7 = 0;
        $user->weapon_8 = 0;
        $user->weapon_9 = 0;
        $user->weapon_10 = 0;
        $user->weapon_11 = 0;
        $user->weapon_12 = 0;
        $user->save();

        return back()->with('success', 'Member kicked!');
    }

    public function giveLocker($uid)
    {
        $users = auth()->user();

        $highestRank = DB::table('factionranks')
            ->where('id', $users->faction)
            ->max('rank');

        if ($users->factionrank != $highestRank) {
            return back()->with('error', 'Action denied. You are no longer the faction leader.');
        }

        $member = User::findOrFail($uid);

        if($users->faction !== $member->faction) {
            return back()->with('error', 'Action denied. That player is not longer your faction member.');
        }

        $member->lockerl = 1;
        $member->save();

        return back()->with('success', 'Locker leader granted.');
    }

    public function revokeLocker($uid)
    {
        $users = auth()->user();

        $highestRank = DB::table('factionranks')
            ->where('id', $users->faction)
            ->max('rank');

        if ($users->factionrank != $highestRank) {
            return back()->with('error', 'Action denied. You are no longer the faction leader.');
        }

        $member = User::findOrFail($uid);

        if($users->faction !== $member->faction) {
            return back()->with('error', 'Action denied. That player is not longer your faction member.');
        }

        $member->lockerl = 0;
        $member->save();

        return back()->with('success', 'Locker leader revoked.');
    }

    public function countFactionMembers($factionId)
    {
        return User::where('faction', $factionId)->count();
    }

    public function updateRank(Request $request, $uid)
    {
        $users = auth()->user();

        $highestRank = DB::table('factionranks')
            ->where('id', $users->faction)
            ->max('rank');

        if ($users->factionrank != $highestRank) {
            return back()->with('error', 'Action denied. You are no longer the faction leader.');
        }
        
        $request->validate([
            'factionrank' => 'required|integer',
        ]);

        DB::table('users')
            ->where('uid', $uid)
            ->update(['factionrank' => $request->factionrank]);

        return back()->with('success', 'Faction rank updated successfully.');
    }

}
