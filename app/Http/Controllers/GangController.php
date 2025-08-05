<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'mainContent' => 'gang.management',
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
        $user = User::findOrFail($id);
        $user->gang_id = null;
        $user->save();

        return back()->with('success', 'Member kicked!');
    }

    public function updateRanks(Request $request)
    {
        foreach ($request->ranks as $id => $data) {
            $rank = GangRank::find($id);
            $rank->name = $data['name'];
            $rank->skin = $data['skin'];
            $rank->save();
        }

        return back()->with('success', 'Ranks updated.');
    }

    public function countGangMembers($gangId)
    {
        return User::where('gang', $gangId)->count();
    }

    public function updateRank(Request $request, $uid)
    {
        $request->validate([
            'gangrank' => 'required|integer',
        ]);

        DB::table('users')
            ->where('uid', $uid)
            ->update(['gangrank' => $request->gangrank]);

        return back()->with('success', 'Gang rank updated successfully.');
    }

}
