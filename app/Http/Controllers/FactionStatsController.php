<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Faction;
use App\Models\User;

class FactionStatsController extends Controller
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
            'mainContent' => 'faction.stats',
            'faction' => $faction,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'factionranks' => $factionranks,
        ]);
    }

    public function inventory()
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
            'mainContent' => 'faction.inventory',
            'faction' => $faction,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'factionranks' => $factionranks,
        ]);
    }

    public function logs(Request $request)
    {
        $user = auth()->user();

        // Get the user's faction
        $faction = Faction::find($user->faction);

        // Fetch faction members (users in the same faction)
        $members = User::where('faction', $faction->id)->orderByDesc('factionrank')->get();

        // Fetch ranks belonging to the faction
        $ranks = DB::table('factionranks')->where('id', $faction->id)->get();

        $factionranks = DB::table('factionranks')->get();


        $search = $request->input('search');

        $logs = DB::table('log_faction')
            ->where('description', 'like', '%(factionid: ' . $user->faction . ')%')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'faction.logs',
            'faction' => $faction,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'factionranks' => $factionranks,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function lockerlogs(Request $request)
    {
        $user = auth()->user();

        // Get the user's faction
        $faction = Faction::find($user->faction);

        // Fetch faction members (users in the same faction)
        $members = User::where('faction', $faction->id)->orderByDesc('factionrank')->get();

        // Fetch ranks belonging to the faction
        $ranks = DB::table('factionranks')->where('id', $faction->id)->get();

        $factionranks = DB::table('factionranks')->get();


        $search = $request->input('search');

        $logs = DB::table('log_factionlocker')
            ->where('description', 'like', '%(factionid: ' . $user->faction . ')%')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'faction.lockerlogs',
            'faction' => $faction,
            'user' => $user,
            'members' => $members,
            'ranks' => $ranks,
            'factionranks' => $factionranks,
            'search' => $search,
            'logs' => $logs,
        ]);
    }
}
