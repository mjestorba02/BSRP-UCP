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

        $userUid = auth()->user()->uid;

        $houses = DB::table('houses')->where('ownerid', $userUid)->get();
        $businesses = DB::table('businesses')->where('ownerid', $userUid)->get();

        $vehicles = DB::table('vehicles')->where('ownerid', $userUid)->get();

        $businessTypeMap = [
            0 => "24/7",
            1 => "Ammunation",
            2 => "Clothing Line",
            3 => "Gym",
            4 => "Ingredients Shop",
            5 => "Advertisement Agency",
            6 => "Club/Bar",
            7 => "Dealership",
            8 => "Hardware",
            9 => "Electronics Shop"
        ];

        return view('dashboard', [
            'mainContent' => 'profile.profile',
            'user' => $user,
            'gangs' => $gangs,
            'gangranks' => $gangranks,
            'factions' => $factions,
            'factionranks' => $gfactionranks,
            'houses' => $houses,
            'businesses' => $businesses,
            'vehicles' => $vehicles,
            'businessTypeMap' => $businessTypeMap,
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

    public function getBanHistory($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $banHistory = DB::table('log_bans')
            ->where('uid', $user->uid)
            ->orderBy('date', 'desc')
            ->get(['date', 'description']);

        if ($banHistory->isEmpty()) {
            return response()->json(['message' => 'No ban history found'], 200);
        }

        return response()->json($banHistory);
    }

    function getOwnershipStatus($timestamp, $donator_level) {
        $now = time();
        $INACTIVE_TIME = 60 * 60 * 24 * 14; // 2 weeks
        $INACTIVE_TIME_2 = 60 * 60 * 24 * 21; // 3 weeks

        $inactive_time = $donator_level >= 2 ? $INACTIVE_TIME_2 : $INACTIVE_TIME;

        if (($now - $timestamp) > $inactive_time) {
            return ['status' => 'Inactive', 'color' => '#FFA500']; // Orange
        } else {
            return ['status' => 'Active', 'color' => '#27ff81ff']; // White
        }
    }

    private $vehicleNames = [
        "Landstalker", "Bravura", "Buffalo", "Linerunner", "Perrenial", "Sentinel", "Dumper", "Firetruck", "Trashmaster",
        "Stretch", "Manana", "Infernus", "Voodoo", "Pony", "Mule", "Cheetah", "Ambulance", "Leviathan", "Moonbeam",
        "Esperanto", "Taxi", "Washington", "Bobcat", "Whoopee", "BF Injection", "Hunter", "Premier", "Enforcer",
        "Securicar", "Banshee", "Predator", "Bus", "Rhino", "Barracks", "Hotknife", "Article Trailer", "Previon", "Coach",
        "Cabbie", "Stallion", "Rumpo", "RC Bandit", "Romero", "Packer", "Monster", "Admiral", "Squalo", "Seasparrow",
        "Pizzaboy", "Tram", "Article Trailer 2", "Turismo", "Speeder", "Reefer", "Tropic", "Flatbed", "Yankee", "Caddy", "Solair",
        "Berkley's RC Van", "Skimmer", "PCJ-600", "Faggio", "Freeway", "RC Baron", "RC Raider", "Glendale", "Oceanic",
        "Sanchez", "Sparrow", "Patriot", "Quad", "Coastguard", "Dinghy", "Hermes", "Sabre", "Rustler", "ZR-350", "Walton",
        "Regina", "Comet", "BMX", "Burrito", "Camper", "Marquis", "Baggage", "Dozer", "Maverick", "News Chopper", "Rancher",
        "FBI Rancher", "Virgo", "Greenwood", "Jetmax", "Hotring", "Sandking", "Blista Compact", "Police Maverick",
        "Boxville", "Benson", "Mesa", "RC Goblin", "Hotring Racer A", "Hotring Racer B", "Bloodring Banger", "Rancher",
        "Super GT", "Elegant", "Journey", "Bike", "Mountain Bike", "Beagle", "Cropduster", "Stuntplane", "Tanker", "Roadtrain",
        "Nebula", "Majestic", "Buccaneer", "Shamal", "Hydra", "FCR-900", "NRG-500", "HPV1000", "Cement Truck", "Tow Truck",
        "Fortune", "Cadrona", "SWAT Truck", "Willard", "Forklift", "Tractor", "Combine", "Feltzer", "Remington", "Slamvan",
        "Blade", "Streak", "Freight", "Vortex", "Vincent", "Bullet", "Clover", "Sadler", "Firetruck", "Hustler", "Intruder",
        "Primo", "Cargobob", "Tampa", "Sunrise", "Merit", "Utility", "Nevada", "Yosemite", "Windsor", "Monster", "Monster",
        "Uranus", "Jester", "Sultan", "Stratum", "Elegy", "Raindance", "RC Tiger", "Flash", "Tahoma", "Savanna", "Bandito",
        "Freight Flat", "Streak Carriage", "Kart", "Mower", "Dune", "Sweeper", "Broadway", "Tornado", "AT-400", "DFT-30",
        "Huntley", "Stafford", "BF-400", "News Van", "Tug", "Petrol Trailer", "Emperor", "Wayfarer", "Euros", "Hotdog", "Club",
        "Freight Box", "Article Trailer 3", "Andromada", "Dodo", "RC Cam", "Launch", "LSPD Car", "SFPD Car", "LVPD Car",
        "Police Rancher", "Picador", "S.W.A.T", "Alpha", "Phoenix", "Glendale", "Sadler", "Luggage", "Luggage", "Stairs",
        "Boxville", "Tiller", "Utility Trailer"
    ];

    function getVehicleName(int $modelId): string
    {
        if ($modelId >= 400 && $modelId <= 611) {
            return $this->vehicleNames[$modelId - 400] ?? 'Unknown';
        }

        return 'Unknown';
    }
}
