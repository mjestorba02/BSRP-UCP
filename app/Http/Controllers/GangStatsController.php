<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Gang;
use App\Models\User;

class GangStatsController extends Controller
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

        $turfsCaptured = DB::table('turfs')->where('capturedgang', $user->gang)->count();

        return view('dashboard', [
            'mainContent' => 'gang.stats',
            'gang' => $gang,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'gangranks' => $gangranks,
            'turfsCaptured' => $turfsCaptured,
        ]);
    }

    public function inventory()
    {
        $user = auth()->user();

        // Get the user's gang
        $gang = Gang::find($user->gang);

        // Fetch gang members (users in the same gang)
        $members = User::where('gang', $gang->id)->orderByDesc('gangrank')->get();

        // Fetch ranks belonging to the gang
        $ranks = DB::table('gangranks')->where('id', $gang->id)->get();

        $gangranks = DB::table('gangranks')->get();

        $turfsCaptured = DB::table('turfs')->where('capturedgang', $user->gang)->count();

        return view('dashboard', [
            'mainContent' => 'gang.inventory',
            'gang' => $gang,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'gangranks' => $gangranks,
            'turfsCaptured' => $turfsCaptured,
        ]);
    }

    public function logs(Request $request)
    {
        $user = auth()->user();

        // Get the user's gang
        $gang = Gang::find($user->gang);

        // Fetch gang members (users in the same gang)
        $members = User::where('gang', $gang->id)->orderByDesc('gangrank')->get();

        // Fetch ranks belonging to the gang
        $ranks = DB::table('gangranks')->where('id', $gang->id)->get();

        $gangranks = DB::table('gangranks')->get();

        $turfsCaptured = DB::table('turfs')->where('capturedgang', $user->gang)->count();

        $search = $request->input('search');

        $logs = DB::table('log_gangstash')
            ->where('description', 'like', '%(gangid: ' . $user->gang . ')%')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'gang.logs',
            'gang' => $gang,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'gangranks' => $gangranks,
            'turfsCaptured' => $turfsCaptured,
            'search' => $search,
            'logs' => $logs,
        ]);
    }
}
