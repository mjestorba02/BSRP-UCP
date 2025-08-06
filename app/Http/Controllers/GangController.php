<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Gang;
use App\Models\User;

class GangController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get the user's gang
        $gang = Gang::find($user->gang);

        // Fetch gang members (users in the same gang)
        $members = User::where('gang', $gang->id)->orderByDesc('gangrank')->get();

        // Fetch ranks belonging to the gang
        $ranks = DB::table('gangranks')->where('id', $gang->id)->get();

        $gangranks = DB::table('gangranks')->get();

        return view('dashboard', [
            'mainContent' => 'gang.members',
            'gang' => $gang,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'gangranks' => $gangranks,
        ]);
    }

    public function invite(Request $request)
    {
        $user = User::where('username', $request->username)->firstOrFail();
        $user->gang_id = auth()->user()->gang_id;
        $user->save();

        return back()->with('success', 'Member invited!');
    }

    public function kick($id)
    {
        $users = auth()->user();

        if ($users->gangrank !== 6) {
            return back()->with('error', 'Action denied. You are no longer Gang Leader (Rank 6).');
        }

        $user = User::findOrFail($id);

        if($users->gang !== $user->gang) {
            return back()->with('error', 'Action denied. That player is not longer your gang member.');
        }

        $user->gang = -1;
        $user->walkietalkievcc = 0;
        $user->gangrank = 0;
        $user->save();

        return back()->with('success', 'Member kicked!');
    }

    public function giveLocker($uid)
    {
        $users = auth()->user();

        if ($users->gangrank !== 6) {
            return back()->with('error', 'Action denied. You are not no longer a gang leader (Rank 6).');
        }

        $member = User::findOrFail($uid);

        if($users->gang !== $member->gang) {
            return back()->with('error', 'Action denied. That player is not longer your gang member.');
        }

        $member->glockerl = 1;
        $member->save();

        return back()->with('success', 'Locker leader granted.');
    }

    public function revokeLocker($uid)
    {
        $users = auth()->user();

        if ($users->gangrank !== 6) {
            return back()->with('error', 'Action denied. You are not no longer a gang leader (Rank 6).');
        }

        $member = User::findOrFail($uid);

        if($users->gang !== $member->gang) {
            return back()->with('error', 'Action denied. That player is not longer your gang member.');
        }

        $member->glockerl = 0;
        $member->save();

        return back()->with('success', 'Locker leader revoked.');
    }

    public function countGangMembers($gangId)
    {
        return User::where('gang', $gangId)->count();
    }

    public function updateRank(Request $request, $uid)
    {
        $Authusers = auth()->user();

        if ($Authusers->gangrank !== 6) {
            return back()->with('error', 'Action denied. You are not no longer a gang leader (Rank 6).');
        }

        $request->validate([
            'gangrank' => 'required|integer',
        ]);

        DB::table('users')
            ->where('uid', $uid)
            ->update(['gangrank' => $request->gangrank]);

        return back()->with('success', 'Gang rank updated successfully.');
    }

}
