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

    public function getBizObject(int $bizType): array
    {
        $bizMap = [
            0 => ['object' => 1274, 'name' => '24/7 Store'],
            1 => ['object' => 2061, 'name' => 'Ammunition'],
            2 => ['object' => 1275, 'name' => 'Clothing Line'],
            3 => ['object' => 1314, 'name' => 'Gym'],
            4 => ['object' => 1582, 'name' => 'Restaurant Shop'],
            5 => ['object' => 1210, 'name' => 'Advertisement Agency'],
            6 => ['object' => 1314, 'name' => 'Club/Bar'],
            7 => ['object' => 1210, 'name' => 'Dealership'],
            8 => ['object' => 19832, 'name' => 'Hardware'],
            9 => ['object' => 1314, 'name' => 'Electronics Shop'],
        ];

        return $bizMap[$bizType] ?? ['object' => 18631, 'name' => 'Custom Business'];
    }

    public function getHouseType(int $interiorId): string
    {
        return match (true) {
            $interiorId >= 1 && $interiorId <= 6  => 'Apartment',
            $interiorId >= 7 && $interiorId <= 9  => 'Low Class',
            $interiorId >= 10 && $interiorId <= 12 => 'Medium Class',
            $interiorId >= 13 && $interiorId <= 16 => 'Upper Class',
            $interiorId >= 17 && $interiorId <= 19 => 'Mansion',
            $interiorId === 20                     => 'Custom House',
            default                                => 'Unknown',
        };
    }


    private $vehicleNames = [
        "Landstalker", "Bravura", "Buffalo", "Linerunner", "Perrenial", "Sentinel", "Dumper", "Firetruck", "Trashmaster",
        "Stretch", "Manana", "Infernus", "Voodoo", "Pony", "Mule", "Cheetah", "Ambulance", "Leviathan", "Moonbeam",
        "Esperanto", "Taxi", "Washington", "Bobcat", "Whoopee", "BF Injection", "Hunter", "Premier", "Enforcer",
        "Securicar", "Banshee", "Predator", "Bus", "Rhino", "Barracks", "Hotknife", "Article Trailer", "Previon", "Coach",
        "Cabbie", "Stallion", "Rumpo", "RC Bandit", "Romero", "Packer", "Monster", "Admiral", "Squalo", "Seasparrow",
        "Pizzaboy", "Tram", "Article Trailer 2", "Turismo", "Speeder", "Reefer", "Tropic", "Flatbed", "Yankee", "Caddy", "Solair",
        "Berkleys RC Van", "Skimmer", "PCJ-600", "Faggio", "Freeway", "RC Baron", "RC Raider", "Glendale", "Oceanic",
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

    private array $zones = [
        ['name' => 'The Big Ear', 'minX' => -410.00, 'minY' => 1403.30, 'minZ' => -3.00, 'maxX' => -137.90, 'maxY' => 1681.20, 'maxZ' => 200.00],
        ['name' => 'Aldea Malvada', 'minX' => -1372.10, 'minY' => 2498.50, 'minZ' => 0.00, 'maxX' => -1277.50, 'maxY' => 2615.30, 'maxZ' => 200.00],
        ['name' => 'Angel Pine', 'minX' => -2324.90, 'minY' => -2584.20, 'minZ' => -6.10, 'maxX' => -1964.20, 'maxY' => -2212.10, 'maxZ' => 200.00],
        ['name' => 'Arco del Oeste', 'minX' => -901.10, 'minY' => 2221.80, 'minZ' => 0.00, 'maxX' => -592.00, 'maxY' => 2571.90, 'maxZ' => 200.00],
        ['name' => 'Avispa Country Club', 'minX' => -2646.40, 'minY' => -355.40, 'minZ' => 0.00, 'maxX' => -2270.00, 'maxY' => -222.50, 'maxZ' => 200.00],
        ['name' => 'Avispa Country Club', 'minX' => -2831.80, 'minY' => -430.20, 'minZ' => -6.10, 'maxX' => -2646.40, 'maxY' => -222.50, 'maxZ' => 200.00],
        ['name' => 'Avispa Country Club', 'minX' => -2361.50, 'minY' => -417.10, 'minZ' => 0.00, 'maxX' => -2270.00, 'maxY' => -355.40, 'maxZ' => 200.00],
        ['name' => 'Avispa Country Club', 'minX' => -2667.80, 'minY' => -302.10, 'minZ' => -28.80, 'maxX' => -2646.40, 'maxY' => -262.30, 'maxZ' => 71.10],
        ['name' => 'Avispa Country Club', 'minX' => -2470.00, 'minY' => -355.40, 'minZ' => 0.00, 'maxX' => -2270.00, 'maxY' => -318.40, 'maxZ' => 46.10],
        ['name' => 'Avispa Country Club', 'minX' => -2550.00, 'minY' => -355.40, 'minZ' => 0.00, 'maxX' => -2470.00, 'maxY' => -318.40, 'maxZ' => 39.70],
        ['name' => 'Back o Beyond', 'minX' => -1166.90, 'minY' => -2641.10, 'minZ' => 0.00, 'maxX' => -321.70, 'maxY' => -1856.00, 'maxZ' => 200.00],
        ['name' => 'Battery Point', 'minX' => -2741.00, 'minY' => 1268.40, 'minZ' => -4.50, 'maxX' => -2533.00, 'maxY' => 1490.40, 'maxZ' => 200.00],
        ['name' => 'Bayside', 'minX' => -2741.00, 'minY' => 2175.10, 'minZ' => 0.00, 'maxX' => -2353.10, 'maxY' => 2722.70, 'maxZ' => 200.00],
        ['name' => 'Bayside Marina', 'minX' => -2353.10, 'minY' => 2275.70, 'minZ' => 0.00, 'maxX' => -2153.10, 'maxY' => 2475.70, 'maxZ' => 200.00],
        ['name' => 'Beacon Hill', 'minX' => -399.60, 'minY' => -1075.50, 'minZ' => -1.40, 'maxX' => -319.00, 'maxY' => -977.50, 'maxZ' => 198.50],
        ['name' => 'Blackfield', 'minX' => 964.30, 'minY' => 1203.20, 'minZ' => -89.00, 'maxX' => 1197.30, 'maxY' => 1403.20, 'maxZ' => 110.90],
        ['name' => 'Blackfield', 'minX' => 964.30, 'minY' => 1403.20, 'minZ' => -89.00, 'maxX' => 1197.30, 'maxY' => 1726.20, 'maxZ' => 110.90],
        ['name' => 'Blackfield Chapel', 'minX' => 1375.60, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 1558.00, 'maxY' => 823.20, 'maxZ' => 110.90],
        ['name' => 'Blackfield Chapel', 'minX' => 1325.60, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 1375.60, 'maxY' => 795.00, 'maxZ' => 110.90],
        ['name' => 'Blackfield Intersection', 'minX' => 1197.30, 'minY' => 1044.60, 'minZ' => -89.00, 'maxX' => 1277.00, 'maxY' => 1163.30, 'maxZ' => 110.90],
        ['name' => 'Blackfield Intersection', 'minX' => 1166.50, 'minY' => 795.00, 'minZ' => -89.00, 'maxX' => 1375.60, 'maxY' => 1044.60, 'maxZ' => 110.90],
        ['name' => 'Blackfield Intersection', 'minX' => 1277.00, 'minY' => 1044.60, 'minZ' => -89.00, 'maxX' => 1315.30, 'maxY' => 1087.60, 'maxZ' => 110.90],
        ['name' => 'Blackfield Intersection', 'minX' => 1375.60, 'minY' => 823.20, 'minZ' => -89.00, 'maxX' => 1457.30, 'maxY' => 919.40, 'maxZ' => 110.90],
        ['name' => 'Poblacion', 'minX' => 104.50, 'minY' => -220.10, 'minZ' => 2.30, 'maxX' => 349.60, 'maxY' => 152.20, 'maxZ' => 200.00],
        ['name' => 'Poblacion', 'minX' => 19.60, 'minY' => -404.10, 'minZ' => 3.80, 'maxX' => 349.60, 'maxY' => -220.10, 'maxZ' => 200.00],
        ['name' => 'Poblacion Acres', 'minX' => -319.60, 'minY' => -220.10, 'minZ' => 0.00, 'maxX' => 104.50, 'maxY' => 293.30, 'maxZ' => 200.00],
        ['name' => 'Caligulas Palace', 'minX' => 2087.30, 'minY' => 1543.20, 'minZ' => -89.00, 'maxX' => 2437.30, 'maxY' => 1703.20, 'maxZ' => 110.90],
        ['name' => 'Caligulas Palace', 'minX' => 2137.40, 'minY' => 1703.20, 'minZ' => -89.00, 'maxX' => 2437.30, 'maxY' => 1783.20, 'maxZ' => 110.90],
        ['name' => 'Calton Heights', 'minX' => -2274.10, 'minY' => 744.10, 'minZ' => -6.10, 'maxX' => -1982.30, 'maxY' => 1358.90, 'maxZ' => 200.00],
        ['name' => 'Chinatown', 'minX' => -2274.10, 'minY' => 578.30, 'minZ' => -7.60, 'maxX' => -2078.60, 'maxY' => 744.10, 'maxZ' => 200.00],
        ['name' => 'Town Hall', 'minX' => -2867.80, 'minY' => 277.40, 'minZ' => -9.10, 'maxX' => -2593.40, 'maxY' => 458.40, 'maxZ' => 200.00],
        ['name' => 'Come-A-Lot', 'minX' => 2087.30, 'minY' => 943.20, 'minZ' => -89.00, 'maxX' => 2623.10, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1323.90, 'minY' => -1842.20, 'minZ' => -89.00, 'maxX' => 1701.90, 'maxY' => -1722.20, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1323.90, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1440.90, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1370.80, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -1384.90, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1463.90, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 1667.90, 'maxY' => -1430.80, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1583.50, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1758.90, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Commerce', 'minX' => 1667.90, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1430.80, 'maxZ' => 110.90],
        ['name' => 'Conference Center', 'minX' => 1046.10, 'minY' => -1804.20, 'minZ' => -89.00, 'maxX' => 1323.90, 'maxY' => -1722.20, 'maxZ' => 110.90],
        ['name' => 'Conference Center', 'minX' => 1073.20, 'minY' => -1842.20, 'minZ' => -89.00, 'maxX' => 1323.90, 'maxY' => -1804.20, 'maxZ' => 110.90],
        ['name' => 'Cranberry Station', 'minX' => -2007.80, 'minY' => 56.30, 'minZ' => 0.00, 'maxX' => -1922.00, 'maxY' => 224.70, 'maxZ' => 100.00],
        ['name' => 'Creek', 'minX' => 2749.90, 'minY' => 1937.20, 'minZ' => -89.00, 'maxX' => 2921.60, 'maxY' => 2669.70, 'maxZ' => 110.90],
        ['name' => 'Brgy. Palanan', 'minX' => 580.70, 'minY' => -674.80, 'minZ' => -9.50, 'maxX' => 861.00, 'maxY' => -404.70, 'maxZ' => 200.00],
        ['name' => 'Doherty', 'minX' => -2270.00, 'minY' => -324.10, 'minZ' => -0.00, 'maxX' => -1794.90, 'maxY' => -222.50, 'maxZ' => 200.00],
        ['name' => 'Doherty', 'minX' => -2173.00, 'minY' => -222.50, 'minZ' => -0.00, 'maxX' => -1794.90, 'maxY' => 265.20, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -1982.30, 'minY' => 744.10, 'minZ' => -6.10, 'maxX' => -1871.70, 'maxY' => 1274.20, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -1871.70, 'minY' => 1176.40, 'minZ' => -4.50, 'maxX' => -1620.30, 'maxY' => 1274.20, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -1700.00, 'minY' => 744.20, 'minZ' => -6.10, 'maxX' => -1580.00, 'maxY' => 1176.50, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -1580.00, 'minY' => 744.20, 'minZ' => -6.10, 'maxX' => -1499.80, 'maxY' => 1025.90, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -2078.60, 'minY' => 578.30, 'minZ' => -7.60, 'maxX' => -1499.80, 'maxY' => 744.20, 'maxZ' => 200.00],
        ['name' => 'Downtown', 'minX' => -1993.20, 'minY' => 265.20, 'minZ' => -9.10, 'maxX' => -1794.90, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Downtown Los Santos', 'minX' => 1463.90, 'minY' => -1430.80, 'minZ' => -89.00, 'maxX' => 1724.70, 'maxY' => -1290.80, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1724.70, 'minY' => -1430.80, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1250.90, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1463.90, 'minY' => -1290.80, 'minZ' => -89.00, 'maxX' => 1724.70, 'maxY' => -1150.80, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1370.80, 'minY' => -1384.90, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -1170.80, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1724.70, 'minY' => -1250.90, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1150.80, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1370.80, 'minY' => -1170.80, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -1130.80, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1378.30, 'minY' => -1130.80, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -1026.30, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1391.00, 'minY' => -1026.30, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -926.90, 'maxZ' => 110.90],
        ['name' => 'Downtown Los Santos', 'minX' => 1507.50, 'minY' => -1385.20, 'minZ' => 110.90, 'maxX' => 1582.50, 'maxY' => -1325.30, 'maxZ' => 335.90],
        ['name' => 'East Beach', 'minX' => 2632.80, 'minY' => -1852.80, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -1668.10, 'maxZ' => 110.90],
        ['name' => 'East Beach', 'minX' => 2632.80, 'minY' => -1668.10, 'minZ' => -89.00, 'maxX' => 2747.70, 'maxY' => -1393.40, 'maxZ' => 110.90],
        ['name' => 'East Beach', 'minX' => 2747.70, 'minY' => -1668.10, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -1498.60, 'maxZ' => 110.90],
        ['name' => 'East Beach', 'minX' => 2747.70, 'minY' => -1498.60, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -1120.00, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2421.00, 'minY' => -1628.50, 'minZ' => -89.00, 'maxX' => 2632.80, 'maxY' => -1454.30, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2222.50, 'minY' => -1628.50, 'minZ' => -89.00, 'maxX' => 2421.00, 'maxY' => -1494.00, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2266.20, 'minY' => -1494.00, 'minZ' => -89.00, 'maxX' => 2381.60, 'maxY' => -1372.00, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2381.60, 'minY' => -1494.00, 'minZ' => -89.00, 'maxX' => 2421.00, 'maxY' => -1454.30, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2281.40, 'minY' => -1372.00, 'minZ' => -89.00, 'maxX' => 2381.60, 'maxY' => -1135.00, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2381.60, 'minY' => -1454.30, 'minZ' => -89.00, 'maxX' => 2462.10, 'maxY' => -1135.00, 'maxZ' => 110.90],
        ['name' => 'East Los Santos', 'minX' => 2462.10, 'minY' => -1454.30, 'minZ' => -89.00, 'maxX' => 2581.70, 'maxY' => -1135.00, 'maxZ' => 110.90],
        ['name' => 'Easter Basin', 'minX' => -1794.90, 'minY' => 249.90, 'minZ' => -9.10, 'maxX' => -1242.90, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Easter Basin', 'minX' => -1794.90, 'minY' => -50.00, 'minZ' => -0.00, 'maxX' => -1499.80, 'maxY' => 249.90, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1499.80, 'minY' => -50.00, 'minZ' => -0.00, 'maxX' => -1242.90, 'maxY' => 249.90, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1794.90, 'minY' => -730.10, 'minZ' => -3.00, 'maxX' => -1213.90, 'maxY' => -50.00, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1213.90, 'minY' => -730.10, 'minZ' => 0.00, 'maxX' => -1132.80, 'maxY' => -50.00, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1242.90, 'minY' => -50.00, 'minZ' => 0.00, 'maxX' => -1213.90, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1213.90, 'minY' => -50.00, 'minZ' => -4.50, 'maxX' => -947.90, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Airport', 'minX' => -1315.40, 'minY' => -405.30, 'minZ' => 15.40, 'maxX' => -1264.40, 'maxY' => -209.50, 'maxZ' => 25.40],
        ['name' => 'Easter Bay Airport', 'minX' => -1354.30, 'minY' => -287.30, 'minZ' => 15.40, 'maxX' => -1315.40, 'maxY' => -209.50, 'maxZ' => 25.40],
        ['name' => 'Easter Bay Airport', 'minX' => -1490.30, 'minY' => -209.50, 'minZ' => 15.40, 'maxX' => -1264.40, 'maxY' => -148.30, 'maxZ' => 25.40],
        ['name' => 'Easter Bay Chemicals', 'minX' => -1132.80, 'minY' => -768.00, 'minZ' => 0.00, 'maxX' => -956.40, 'maxY' => -578.10, 'maxZ' => 200.00],
        ['name' => 'Easter Bay Chemicals', 'minX' => -1132.80, 'minY' => -787.30, 'minZ' => 0.00, 'maxX' => -956.40, 'maxY' => -768.00, 'maxZ' => 200.00],
        ['name' => 'El Castillo del Diablo', 'minX' => -464.50, 'minY' => 2217.60, 'minZ' => 0.00, 'maxX' => -208.50, 'maxY' => 2580.30, 'maxZ' => 200.00],
        ['name' => 'El Castillo del Diablo', 'minX' => -208.50, 'minY' => 2123.00, 'minZ' => -7.60, 'maxX' => 114.00, 'maxY' => 2337.10, 'maxZ' => 200.00],
        ['name' => 'El Castillo del Diablo', 'minX' => -208.50, 'minY' => 2337.10, 'minZ' => 0.00, 'maxX' => 8.40, 'maxY' => 2487.10, 'maxZ' => 200.00],
        ['name' => 'El Corona', 'minX' => 1812.60, 'minY' => -2179.20, 'minZ' => -89.00, 'maxX' => 1970.60, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'El Corona', 'minX' => 1692.60, 'minY' => -2179.20, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1842.20, 'maxZ' => 110.90],
        ['name' => 'El Quebrados', 'minX' => -1645.20, 'minY' => 2498.50, 'minZ' => 0.00, 'maxX' => -1372.10, 'maxY' => 2777.80, 'maxZ' => 200.00],
        ['name' => 'Esplanade East', 'minX' => -1620.30, 'minY' => 1176.50, 'minZ' => -4.50, 'maxX' => -1580.00, 'maxY' => 1274.20, 'maxZ' => 200.00],
        ['name' => 'Esplanade East', 'minX' => -1580.00, 'minY' => 1025.90, 'minZ' => -6.10, 'maxX' => -1499.80, 'maxY' => 1274.20, 'maxZ' => 200.00],
        ['name' => 'Esplanade East', 'minX' => -1499.80, 'minY' => 578.30, 'minZ' => -79.60, 'maxX' => -1339.80, 'maxY' => 1274.20, 'maxZ' => 20.30],
        ['name' => 'Esplanade North', 'minX' => -2533.00, 'minY' => 1358.90, 'minZ' => -4.50, 'maxX' => -1996.60, 'maxY' => 1501.20, 'maxZ' => 200.00],
        ['name' => 'Esplanade North', 'minX' => -1996.60, 'minY' => 1358.90, 'minZ' => -4.50, 'maxX' => -1524.20, 'maxY' => 1592.50, 'maxZ' => 200.00],
        ['name' => 'Esplanade North', 'minX' => -1982.30, 'minY' => 1274.20, 'minZ' => -4.50, 'maxX' => -1524.20, 'maxY' => 1358.90, 'maxZ' => 200.00],
        ['name' => 'Fallen Tree', 'minX' => -792.20, 'minY' => -698.50, 'minZ' => -5.30, 'maxX' => -452.40, 'maxY' => -380.00, 'maxZ' => 200.00],
        ['name' => 'Fallow Bridge', 'minX' => 434.30, 'minY' => 366.50, 'minZ' => 0.00, 'maxX' => 603.00, 'maxY' => 555.60, 'maxZ' => 200.00],
        ['name' => 'Fern Ridge', 'minX' => 508.10, 'minY' => -139.20, 'minZ' => 0.00, 'maxX' => 1306.60, 'maxY' => 119.50, 'maxZ' => 200.00],
        ['name' => 'Financial', 'minX' => -1871.70, 'minY' => 744.10, 'minZ' => -6.10, 'maxX' => -1701.30, 'maxY' => 1176.40, 'maxZ' => 300.00],
        ['name' => 'Fishers Lagoon', 'minX' => 1916.90, 'minY' => -233.30, 'minZ' => -100.00, 'maxX' => 2131.70, 'maxY' => 13.80, 'maxZ' => 200.00],
        ['name' => 'Flint Intersection', 'minX' => -187.70, 'minY' => -1596.70, 'minZ' => -89.00, 'maxX' => 17.00, 'maxY' => -1276.60, 'maxZ' => 110.90],
        ['name' => 'Flint Range', 'minX' => -594.10, 'minY' => -1648.50, 'minZ' => 0.00, 'maxX' => -187.70, 'maxY' => -1276.60, 'maxZ' => 200.00],
        ['name' => 'Fort Carson', 'minX' => -376.20, 'minY' => 826.30, 'minZ' => -3.00, 'maxX' => 123.70, 'maxY' => 1220.40, 'maxZ' => 200.00],
        ['name' => 'Foster Valley', 'minX' => -2270.00, 'minY' => -430.20, 'minZ' => -0.00, 'maxX' => -2178.60, 'maxY' => -324.10, 'maxZ' => 200.00],
        ['name' => 'Foster Valley', 'minX' => -2178.60, 'minY' => -599.80, 'minZ' => -0.00, 'maxX' => -1794.90, 'maxY' => -324.10, 'maxZ' => 200.00],
        ['name' => 'Foster Valley', 'minX' => -2178.60, 'minY' => -1115.50, 'minZ' => 0.00, 'maxX' => -1794.90, 'maxY' => -599.80, 'maxZ' => 200.00],
        ['name' => 'Foster Valley', 'minX' => -2178.60, 'minY' => -1250.90, 'minZ' => 0.00, 'maxX' => -1794.90, 'maxY' => -1115.50, 'maxZ' => 200.00],
        ['name' => 'Frederick Bridge', 'minX' => 2759.20, 'minY' => 296.50, 'minZ' => 0.00, 'maxX' => 2774.20, 'maxY' => 594.70, 'maxZ' => 200.00],
        ['name' => 'Gant Bridge', 'minX' => -2741.40, 'minY' => 1659.60, 'minZ' => -6.10, 'maxX' => -2616.40, 'maxY' => 2175.10, 'maxZ' => 200.00],
        ['name' => 'Gant Bridge', 'minX' => -2741.00, 'minY' => 1490.40, 'minZ' => -6.10, 'maxX' => -2616.40, 'maxY' => 1659.60, 'maxZ' => 200.00],
        ['name' => 'Ganton', 'minX' => 2222.50, 'minY' => -1852.80, 'minZ' => -89.00, 'maxX' => 2632.80, 'maxY' => -1722.30, 'maxZ' => 110.90],
        ['name' => 'Ganton', 'minX' => 2222.50, 'minY' => -1722.30, 'minZ' => -89.00, 'maxX' => 2632.80, 'maxY' => -1628.50, 'maxZ' => 110.90],
        ['name' => 'Garcia', 'minX' => -2411.20, 'minY' => -222.50, 'minZ' => -0.00, 'maxX' => -2173.00, 'maxY' => 265.20, 'maxZ' => 200.00],
        ['name' => 'Garcia', 'minX' => -2395.10, 'minY' => -222.50, 'minZ' => -5.30, 'maxX' => -2354.00, 'maxY' => -204.70, 'maxZ' => 200.00],
        ['name' => 'Garver Bridge', 'minX' => -1339.80, 'minY' => 828.10, 'minZ' => -89.00, 'maxX' => -1213.90, 'maxY' => 1057.00, 'maxZ' => 110.90],
        ['name' => 'Garver Bridge', 'minX' => -1213.90, 'minY' => 950.00, 'minZ' => -89.00, 'maxX' => -1087.90, 'maxY' => 1178.90, 'maxZ' => 110.90],
        ['name' => 'Garver Bridge', 'minX' => -1499.80, 'minY' => 696.40, 'minZ' => -179.60, 'maxX' => -1339.80, 'maxY' => 925.30, 'maxZ' => 20.30],
        ['name' => 'Glen Park', 'minX' => 1812.60, 'minY' => -1449.60, 'minZ' => -89.00, 'maxX' => 1996.90, 'maxY' => -1350.70, 'maxZ' => 110.90],
        ['name' => 'Glen Park', 'minX' => 1812.60, 'minY' => -1100.80, 'minZ' => -89.00, 'maxX' => 1994.30, 'maxY' => -973.30, 'maxZ' => 110.90],
        ['name' => 'Glen Park', 'minX' => 1812.60, 'minY' => -1350.70, 'minZ' => -89.00, 'maxX' => 2056.80, 'maxY' => -1100.80, 'maxZ' => 110.90],
        ['name' => 'Green Palms', 'minX' => 176.50, 'minY' => 1305.40, 'minZ' => -3.00, 'maxX' => 338.60, 'maxY' => 1520.70, 'maxZ' => 200.00],
        ['name' => 'Greenglass College', 'minX' => 964.30, 'minY' => 1044.60, 'minZ' => -89.00, 'maxX' => 1197.30, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'Greenglass College', 'minX' => 964.30, 'minY' => 930.80, 'minZ' => -89.00, 'maxX' => 1166.50, 'maxY' => 1044.60, 'maxZ' => 110.90],
        ['name' => 'Hampton Barns', 'minX' => 603.00, 'minY' => 264.30, 'minZ' => 0.00, 'maxX' => 761.90, 'maxY' => 366.50, 'maxZ' => 200.00],
        ['name' => 'Hankypanky Point', 'minX' => 2576.90, 'minY' => 62.10, 'minZ' => 0.00, 'maxX' => 2759.20, 'maxY' => 385.50, 'maxZ' => 200.00],
        ['name' => 'Harry Gold Parkway', 'minX' => 1777.30, 'minY' => 863.20, 'minZ' => -89.00, 'maxX' => 1817.30, 'maxY' => 2342.80, 'maxZ' => 110.90],
        ['name' => 'Hashbury', 'minX' => -2593.40, 'minY' => -222.50, 'minZ' => -0.00, 'maxX' => -2411.20, 'maxY' => 54.70, 'maxZ' => 200.00],
        ['name' => 'Hilltop Farm', 'minX' => 967.30, 'minY' => -450.30, 'minZ' => -3.00, 'maxX' => 1176.70, 'maxY' => -217.90, 'maxZ' => 200.00],
        ['name' => 'Hunter Quarry', 'minX' => 337.20, 'minY' => 710.80, 'minZ' => -115.20, 'maxX' => 860.50, 'maxY' => 1031.70, 'maxZ' => 203.70],
        ['name' => 'Idlewood', 'minX' => 1812.60, 'minY' => -1852.80, 'minZ' => -89.00, 'maxX' => 1971.60, 'maxY' => -1742.30, 'maxZ' => 110.90],
        ['name' => 'Idlewood', 'minX' => 1812.60, 'minY' => -1742.30, 'minZ' => -89.00, 'maxX' => 1951.60, 'maxY' => -1602.30, 'maxZ' => 110.90],
        ['name' => 'Idlewood', 'minX' => 1951.60, 'minY' => -1742.30, 'minZ' => -89.00, 'maxX' => 2124.60, 'maxY' => -1602.30, 'maxZ' => 110.90],
        ['name' => 'Idlewood', 'minX' => 1812.60, 'minY' => -1602.30, 'minZ' => -89.00, 'maxX' => 2124.60, 'maxY' => -1449.60, 'maxZ' => 110.90],
        ['name' => 'Idlewood', 'minX' => 2124.60, 'minY' => -1742.30, 'minZ' => -89.00, 'maxX' => 2222.50, 'maxY' => -1494.00, 'maxZ' => 110.90],
        ['name' => 'Idlewood', 'minX' => 1971.60, 'minY' => -1852.80, 'minZ' => -89.00, 'maxX' => 2222.50, 'maxY' => -1742.30, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 1996.90, 'minY' => -1449.60, 'minZ' => -89.00, 'maxX' => 2056.80, 'maxY' => -1350.70, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 2124.60, 'minY' => -1494.00, 'minZ' => -89.00, 'maxX' => 2266.20, 'maxY' => -1449.60, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 2056.80, 'minY' => -1372.00, 'minZ' => -89.00, 'maxX' => 2281.40, 'maxY' => -1210.70, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 2056.80, 'minY' => -1210.70, 'minZ' => -89.00, 'maxX' => 2185.30, 'maxY' => -1126.30, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 2185.30, 'minY' => -1210.70, 'minZ' => -89.00, 'maxX' => 2281.40, 'maxY' => -1154.50, 'maxZ' => 110.90],
        ['name' => 'Jefferson', 'minX' => 2056.80, 'minY' => -1449.60, 'minZ' => -89.00, 'maxX' => 2266.20, 'maxY' => -1372.00, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway East', 'minX' => 2623.10, 'minY' => 943.20, 'minZ' => -89.00, 'maxX' => 2749.90, 'maxY' => 1055.90, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway East', 'minX' => 2685.10, 'minY' => 1055.90, 'minZ' => -89.00, 'maxX' => 2749.90, 'maxY' => 2626.50, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway East', 'minX' => 2536.40, 'minY' => 2442.50, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 2542.50, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway East', 'minX' => 2625.10, 'minY' => 2202.70, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 2442.50, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 2498.20, 'minY' => 2542.50, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 2626.50, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 2237.40, 'minY' => 2542.50, 'minZ' => -89.00, 'maxX' => 2498.20, 'maxY' => 2663.10, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 2121.40, 'minY' => 2508.20, 'minZ' => -89.00, 'maxX' => 2237.40, 'maxY' => 2663.10, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 1938.80, 'minY' => 2508.20, 'minZ' => -89.00, 'maxX' => 2121.40, 'maxY' => 2624.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 1534.50, 'minY' => 2433.20, 'minZ' => -89.00, 'maxX' => 1848.40, 'maxY' => 2583.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 1848.40, 'minY' => 2478.40, 'minZ' => -89.00, 'maxX' => 1938.80, 'maxY' => 2553.40, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 1704.50, 'minY' => 2342.80, 'minZ' => -89.00, 'maxX' => 1848.40, 'maxY' => 2433.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway North', 'minX' => 1377.30, 'minY' => 2433.20, 'minZ' => -89.00, 'maxX' => 1534.50, 'maxY' => 2507.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway South', 'minX' => 1457.30, 'minY' => 823.20, 'minZ' => -89.00, 'maxX' => 2377.30, 'maxY' => 863.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway South', 'minX' => 2377.30, 'minY' => 788.80, 'minZ' => -89.00, 'maxX' => 2537.30, 'maxY' => 897.90, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway West', 'minX' => 1197.30, 'minY' => 1163.30, 'minZ' => -89.00, 'maxX' => 1236.60, 'maxY' => 2243.20, 'maxZ' => 110.90],
        ['name' => 'Julius Thruway West', 'minX' => 1236.60, 'minY' => 2142.80, 'minZ' => -89.00, 'maxX' => 1297.40, 'maxY' => 2243.20, 'maxZ' => 110.90],
        ['name' => 'Juniper Hill', 'minX' => -2533.00, 'minY' => 578.30, 'minZ' => -7.60, 'maxX' => -2274.10, 'maxY' => 968.30, 'maxZ' => 200.00],
        ['name' => 'Juniper Hollow', 'minX' => -2533.00, 'minY' => 968.30, 'minZ' => -6.10, 'maxX' => -2274.10, 'maxY' => 1358.90, 'maxZ' => 200.00],
        ['name' => 'K.A.C.C. Military Fuels', 'minX' => 2498.20, 'minY' => 2626.50, 'minZ' => -89.00, 'maxX' => 2749.90, 'maxY' => 2861.50, 'maxZ' => 110.90],
        ['name' => 'Kincaid Bridge', 'minX' => -1339.80, 'minY' => 599.20, 'minZ' => -89.00, 'maxX' => -1213.90, 'maxY' => 828.10, 'maxZ' => 110.90],
        ['name' => 'Kincaid Bridge', 'minX' => -1213.90, 'minY' => 721.10, 'minZ' => -89.00, 'maxX' => -1087.90, 'maxY' => 950.00, 'maxZ' => 110.90],
        ['name' => 'Kincaid Bridge', 'minX' => -1087.90, 'minY' => 855.30, 'minZ' => -89.00, 'maxX' => -961.90, 'maxY' => 986.20, 'maxZ' => 110.90],
        ['name' => 'Kings', 'minX' => -2329.30, 'minY' => 458.40, 'minZ' => -7.60, 'maxX' => -1993.20, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Kings', 'minX' => -2411.20, 'minY' => 265.20, 'minZ' => -9.10, 'maxX' => -1993.20, 'maxY' => 373.50, 'maxZ' => 200.00],
        ['name' => 'Kings', 'minX' => -2253.50, 'minY' => 373.50, 'minZ' => -9.10, 'maxX' => -1993.20, 'maxY' => 458.40, 'maxZ' => 200.00],
        ['name' => 'LVA Freight Depot', 'minX' => 1457.30, 'minY' => 863.20, 'minZ' => -89.00, 'maxX' => 1777.40, 'maxY' => 1143.20, 'maxZ' => 110.90],
        ['name' => 'LVA Freight Depot', 'minX' => 1375.60, 'minY' => 919.40, 'minZ' => -89.00, 'maxX' => 1457.30, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'LVA Freight Depot', 'minX' => 1277.00, 'minY' => 1087.60, 'minZ' => -89.00, 'maxX' => 1375.60, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'LVA Freight Depot', 'minX' => 1315.30, 'minY' => 1044.60, 'minZ' => -89.00, 'maxX' => 1375.60, 'maxY' => 1087.60, 'maxZ' => 110.90],
        ['name' => 'LVA Freight Depot', 'minX' => 1236.60, 'minY' => 1163.40, 'minZ' => -89.00, 'maxX' => 1277.00, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'Las Barrancas', 'minX' => -926.10, 'minY' => 1398.70, 'minZ' => -3.00, 'maxX' => -719.20, 'maxY' => 1634.60, 'maxZ' => 200.00],
        ['name' => 'Las Brujas', 'minX' => -365.10, 'minY' => 2123.00, 'minZ' => -3.00, 'maxX' => -208.50, 'maxY' => 2217.60, 'maxZ' => 200.00],
        ['name' => 'Las Colinas', 'minX' => 1994.30, 'minY' => -1100.80, 'minZ' => -89.00, 'maxX' => 2056.80, 'maxY' => -920.80, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2056.80, 'minY' => -1126.30, 'minZ' => -89.00, 'maxX' => 2126.80, 'maxY' => -920.80, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2185.30, 'minY' => -1154.50, 'minZ' => -89.00, 'maxX' => 2281.40, 'maxY' => -934.40, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2126.80, 'minY' => -1126.30, 'minZ' => -89.00, 'maxX' => 2185.30, 'maxY' => -934.40, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2747.70, 'minY' => -1120.00, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -945.00, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2632.70, 'minY' => -1135.00, 'minZ' => -89.00, 'maxX' => 2747.70, 'maxY' => -945.00, 'maxZ' => 110.90],
        ['name' => 'Las Colinas', 'minX' => 2281.40, 'minY' => -1135.00, 'minZ' => -89.00, 'maxX' => 2632.70, 'maxY' => -945.00, 'maxZ' => 110.90],
        ['name' => 'Las Payasadas', 'minX' => -354.30, 'minY' => 2580.30, 'minZ' => 2.00, 'maxX' => -133.60, 'maxY' => 2816.80, 'maxZ' => 200.00],
        ['name' => 'Las Venturas Airport', 'minX' => 1236.60, 'minY' => 1203.20, 'minZ' => -89.00, 'maxX' => 1457.30, 'maxY' => 1883.10, 'maxZ' => 110.90],
        ['name' => 'Las Venturas Airport', 'minX' => 1457.30, 'minY' => 1203.20, 'minZ' => -89.00, 'maxX' => 1777.30, 'maxY' => 1883.10, 'maxZ' => 110.90],
        ['name' => 'Las Venturas Airport', 'minX' => 1457.30, 'minY' => 1143.20, 'minZ' => -89.00, 'maxX' => 1777.40, 'maxY' => 1203.20, 'maxZ' => 110.90],
        ['name' => 'Las Venturas Airport', 'minX' => 1515.80, 'minY' => 1586.40, 'minZ' => -12.50, 'maxX' => 1729.90, 'maxY' => 1714.50, 'maxZ' => 87.50],
        ['name' => 'Last Dime Motel', 'minX' => 1823.00, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 1997.20, 'maxY' => 823.20, 'maxZ' => 110.90],
        ['name' => 'Leafy Hollow', 'minX' => -1166.90, 'minY' => -1856.00, 'minZ' => 0.00, 'maxX' => -815.60, 'maxY' => -1602.00, 'maxZ' => 200.00],
        ['name' => 'Liberty Town', 'minX' => -1000.00, 'minY' => 400.00, 'minZ' => 1300.00, 'maxX' => -700.00, 'maxY' => 600.00, 'maxZ' => 1400.00],
        ['name' => 'Lil Probe Inn', 'minX' => -90.20, 'minY' => 1286.80, 'minZ' => -3.00, 'maxX' => 153.80, 'maxY' => 1554.10, 'maxZ' => 200.00],
        ['name' => 'Linden Side', 'minX' => 2749.90, 'minY' => 943.20, 'minZ' => -89.00, 'maxX' => 2923.30, 'maxY' => 1198.90, 'maxZ' => 110.90],
        ['name' => 'Linden Station', 'minX' => 2749.90, 'minY' => 1198.90, 'minZ' => -89.00, 'maxX' => 2923.30, 'maxY' => 1548.90, 'maxZ' => 110.90],
        ['name' => 'Linden Station', 'minX' => 2811.20, 'minY' => 1229.50, 'minZ' => -39.50, 'maxX' => 2861.20, 'maxY' => 1407.50, 'maxZ' => 60.40],
        ['name' => 'Little Mexico', 'minX' => 1701.90, 'minY' => -1842.20, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1722.20, 'maxZ' => 110.90],
        ['name' => 'Little Mexico', 'minX' => 1758.90, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Los Flores', 'minX' => 2581.70, 'minY' => -1454.30, 'minZ' => -89.00, 'maxX' => 2632.80, 'maxY' => -1393.40, 'maxZ' => 110.90],
        ['name' => 'Los Flores', 'minX' => 2581.70, 'minY' => -1393.40, 'minZ' => -89.00, 'maxX' => 2747.70, 'maxY' => -1135.00, 'maxZ' => 110.90],
        ['name' => 'Los Santos International', 'minX' => 1249.60, 'minY' => -2394.30, 'minZ' => -89.00, 'maxX' => 1852.00, 'maxY' => -2179.20, 'maxZ' => 110.90],
        ['name' => 'Los Santos International', 'minX' => 1852.00, 'minY' => -2394.30, 'minZ' => -89.00, 'maxX' => 2089.00, 'maxY' => -2179.20, 'maxZ' => 110.90],
        ['name' => 'Los Santos International', 'minX' => 1382.70, 'minY' => -2730.80, 'minZ' => -89.00, 'maxX' => 2201.80, 'maxY' => -2394.30, 'maxZ' => 110.90],
        ['name' => 'Los Santos International', 'minX' => 1974.60, 'minY' => -2394.30, 'minZ' => -39.00, 'maxX' => 2089.00, 'maxY' => -2256.50, 'maxZ' => 60.90],
        ['name' => 'Los Santos International', 'minX' => 1400.90, 'minY' => -2669.20, 'minZ' => -39.00, 'maxX' => 2189.80, 'maxY' => -2597.20, 'maxZ' => 60.90],
        ['name' => 'Los Santos International', 'minX' => 2051.60, 'minY' => -2597.20, 'minZ' => -39.00, 'maxX' => 2152.40, 'maxY' => -2394.30, 'maxZ' => 60.90],
        ['name' => 'Marina', 'minX' => 647.70, 'minY' => -1804.20, 'minZ' => -89.00, 'maxX' => 851.40, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Marina', 'minX' => 647.70, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 807.90, 'maxY' => -1416.20, 'maxZ' => 110.90],
        ['name' => 'Marina', 'minX' => 807.90, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 926.90, 'maxY' => -1416.20, 'maxZ' => 110.90],
        ['name' => 'Market', 'minX' => 787.40, 'minY' => -1416.20, 'minZ' => -89.00, 'maxX' => 1072.60, 'maxY' => -1310.20, 'maxZ' => 110.90],
        ['name' => 'Market', 'minX' => 952.60, 'minY' => -1310.20, 'minZ' => -89.00, 'maxX' => 1072.60, 'maxY' => -1130.80, 'maxZ' => 110.90],
        ['name' => 'Market', 'minX' => 1072.60, 'minY' => -1416.20, 'minZ' => -89.00, 'maxX' => 1370.80, 'maxY' => -1130.80, 'maxZ' => 110.90],
        ['name' => 'Market', 'minX' => 926.90, 'minY' => -1577.50, 'minZ' => -89.00, 'maxX' => 1370.80, 'maxY' => -1416.20, 'maxZ' => 110.90],
        ['name' => 'Market Station', 'minX' => 787.40, 'minY' => -1410.90, 'minZ' => -34.10, 'maxX' => 866.00, 'maxY' => -1310.20, 'maxZ' => 65.80],
        ['name' => 'Martin Bridge', 'minX' => -222.10, 'minY' => 293.30, 'minZ' => 0.00, 'maxX' => -122.10, 'maxY' => 476.40, 'maxZ' => 200.00],
        ['name' => 'Missionary Hill', 'minX' => -2994.40, 'minY' => -811.20, 'minZ' => 0.00, 'maxX' => -2178.60, 'maxY' => -430.20, 'maxZ' => 200.00],
        ['name' => 'Brgy. Culiat', 'minX' => 1119.50, 'minY' => 119.50, 'minZ' => -3.00, 'maxX' => 1451.40, 'maxY' => 493.30, 'maxZ' => 200.00],
        ['name' => 'Brgy. Culiat', 'minX' => 1451.40, 'minY' => 347.40, 'minZ' => -6.10, 'maxX' => 1582.40, 'maxY' => 420.80, 'maxZ' => 200.00],
        ['name' => 'Culiat Intersection', 'minX' => 1546.60, 'minY' => 208.10, 'minZ' => 0.00, 'maxX' => 1745.80, 'maxY' => 347.40, 'maxZ' => 200.00],
        ['name' => 'Culiat Intersection', 'minX' => 1582.40, 'minY' => 347.40, 'minZ' => 0.00, 'maxX' => 1664.60, 'maxY' => 401.70, 'maxZ' => 200.00],
        ['name' => 'Mulholland', 'minX' => 1414.00, 'minY' => -768.00, 'minZ' => -89.00, 'maxX' => 1667.60, 'maxY' => -452.40, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1281.10, 'minY' => -452.40, 'minZ' => -89.00, 'maxX' => 1641.10, 'maxY' => -290.90, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1269.10, 'minY' => -768.00, 'minZ' => -89.00, 'maxX' => 1414.00, 'maxY' => -452.40, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1357.00, 'minY' => -926.90, 'minZ' => -89.00, 'maxX' => 1463.90, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1318.10, 'minY' => -910.10, 'minZ' => -89.00, 'maxX' => 1357.00, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1169.10, 'minY' => -910.10, 'minZ' => -89.00, 'maxX' => 1318.10, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 768.60, 'minY' => -954.60, 'minZ' => -89.00, 'maxX' => 952.60, 'maxY' => -860.60, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 687.80, 'minY' => -860.60, 'minZ' => -89.00, 'maxX' => 911.80, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 737.50, 'minY' => -768.00, 'minZ' => -89.00, 'maxX' => 1142.20, 'maxY' => -674.80, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 1096.40, 'minY' => -910.10, 'minZ' => -89.00, 'maxX' => 1169.10, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 952.60, 'minY' => -937.10, 'minZ' => -89.00, 'maxX' => 1096.40, 'maxY' => -860.60, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 911.80, 'minY' => -860.60, 'minZ' => -89.00, 'maxX' => 1096.40, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Mulholland', 'minX' => 861.00, 'minY' => -674.80, 'minZ' => -89.00, 'maxX' => 1156.50, 'maxY' => -600.80, 'maxZ' => 110.90],
        ['name' => 'Mulholland Intersection', 'minX' => 1463.90, 'minY' => -1150.80, 'minZ' => -89.00, 'maxX' => 1812.60, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'North Rock', 'minX' => 2285.30, 'minY' => -768.00, 'minZ' => 0.00, 'maxX' => 2770.50, 'maxY' => -269.70, 'maxZ' => 200.00],
        ['name' => 'Ocean Docks', 'minX' => 2373.70, 'minY' => -2697.00, 'minZ' => -89.00, 'maxX' => 2809.20, 'maxY' => -2330.40, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2201.80, 'minY' => -2418.30, 'minZ' => -89.00, 'maxX' => 2324.00, 'maxY' => -2095.00, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2324.00, 'minY' => -2302.30, 'minZ' => -89.00, 'maxX' => 2703.50, 'maxY' => -2145.10, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2089.00, 'minY' => -2394.30, 'minZ' => -89.00, 'maxX' => 2201.80, 'maxY' => -2235.80, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2201.80, 'minY' => -2730.80, 'minZ' => -89.00, 'maxX' => 2324.00, 'maxY' => -2418.30, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2703.50, 'minY' => -2302.30, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -2126.90, 'maxZ' => 110.90],
        ['name' => 'Ocean Docks', 'minX' => 2324.00, 'minY' => -2145.10, 'minZ' => -89.00, 'maxX' => 2703.50, 'maxY' => -2059.20, 'maxZ' => 110.90],
        ['name' => 'Ocean Flats', 'minX' => -2994.40, 'minY' => 277.40, 'minZ' => -9.10, 'maxX' => -2867.80, 'maxY' => 458.40, 'maxZ' => 200.00],
        ['name' => 'Ocean Flats', 'minX' => -2994.40, 'minY' => -222.50, 'minZ' => -0.00, 'maxX' => -2593.40, 'maxY' => 277.40, 'maxZ' => 200.00],
        ['name' => 'Ocean Flats', 'minX' => -2994.40, 'minY' => -430.20, 'minZ' => -0.00, 'maxX' => -2831.80, 'maxY' => -222.50, 'maxZ' => 200.00],
        ['name' => 'Octane Springs', 'minX' => 338.60, 'minY' => 1228.50, 'minZ' => 0.00, 'maxX' => 664.30, 'maxY' => 1655.00, 'maxZ' => 200.00],
        ['name' => 'Old Venturas Strip', 'minX' => 2162.30, 'minY' => 2012.10, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 2202.70, 'maxZ' => 110.90],
        ['name' => 'Palisades', 'minX' => -2994.40, 'minY' => 458.40, 'minZ' => -6.10, 'maxX' => -2741.00, 'maxY' => 1339.60, 'maxZ' => 200.00],
        ['name' => 'Bagong Lipunan', 'minX' => 2160.20, 'minY' => -149.00, 'minZ' => 0.00, 'maxX' => 2576.90, 'maxY' => 228.30, 'maxZ' => 200.00],
        ['name' => 'Paradiso', 'minX' => -2741.00, 'minY' => 793.40, 'minZ' => -6.10, 'maxX' => -2533.00, 'maxY' => 1268.40, 'maxZ' => 200.00],
        ['name' => 'Pershing Square', 'minX' => 1440.90, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1583.50, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Pilgrim', 'minX' => 2437.30, 'minY' => 1383.20, 'minZ' => -89.00, 'maxX' => 2624.40, 'maxY' => 1783.20, 'maxZ' => 110.90],
        ['name' => 'Pilgrim', 'minX' => 2624.40, 'minY' => 1383.20, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 1783.20, 'maxZ' => 110.90],
        ['name' => 'Pilson Intersection', 'minX' => 1098.30, 'minY' => 2243.20, 'minZ' => -89.00, 'maxX' => 1377.30, 'maxY' => 2507.20, 'maxZ' => 110.90],
        ['name' => 'Pirates in Mens Pants', 'minX' => 1817.30, 'minY' => 1469.20, 'minZ' => -89.00, 'maxX' => 2027.40, 'maxY' => 1703.20, 'maxZ' => 110.90],
        ['name' => 'Playa del Seville', 'minX' => 2703.50, 'minY' => -2126.90, 'minZ' => -89.00, 'maxX' => 2959.30, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'Prickle Pine', 'minX' => 1534.50, 'minY' => 2583.20, 'minZ' => -89.00, 'maxX' => 1848.40, 'maxY' => 2863.20, 'maxZ' => 110.90],
        ['name' => 'Prickle Pine', 'minX' => 1117.40, 'minY' => 2507.20, 'minZ' => -89.00, 'maxX' => 1534.50, 'maxY' => 2723.20, 'maxZ' => 110.90],
        ['name' => 'Prickle Pine', 'minX' => 1848.40, 'minY' => 2553.40, 'minZ' => -89.00, 'maxX' => 1938.80, 'maxY' => 2863.20, 'maxZ' => 110.90],
        ['name' => 'Prickle Pine', 'minX' => 1938.80, 'minY' => 2624.20, 'minZ' => -89.00, 'maxX' => 2121.40, 'maxY' => 2861.50, 'maxZ' => 110.90],
        ['name' => 'Queens', 'minX' => -2533.00, 'minY' => 458.40, 'minZ' => 0.00, 'maxX' => -2329.30, 'maxY' => 578.30, 'maxZ' => 200.00],
        ['name' => 'Queens', 'minX' => -2593.40, 'minY' => 54.70, 'minZ' => 0.00, 'maxX' => -2411.20, 'maxY' => 458.40, 'maxZ' => 200.00],
        ['name' => 'Queens', 'minX' => -2411.20, 'minY' => 373.50, 'minZ' => 0.00, 'maxX' => -2253.50, 'maxY' => 458.40, 'maxZ' => 200.00],
        ['name' => 'Randolph Industrial Estate', 'minX' => 1558.00, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 1823.00, 'maxY' => 823.20, 'maxZ' => 110.90],
        ['name' => 'Redsands East', 'minX' => 1817.30, 'minY' => 2011.80, 'minZ' => -89.00, 'maxX' => 2106.70, 'maxY' => 2202.70, 'maxZ' => 110.90],
        ['name' => 'Redsands East', 'minX' => 1817.30, 'minY' => 2202.70, 'minZ' => -89.00, 'maxX' => 2011.90, 'maxY' => 2342.80, 'maxZ' => 110.90],
        ['name' => 'Redsands East', 'minX' => 1848.40, 'minY' => 2342.80, 'minZ' => -89.00, 'maxX' => 2011.90, 'maxY' => 2478.40, 'maxZ' => 110.90],
        ['name' => 'Redsands West', 'minX' => 1236.60, 'minY' => 1883.10, 'minZ' => -89.00, 'maxX' => 1777.30, 'maxY' => 2142.80, 'maxZ' => 110.90],
        ['name' => 'Redsands West', 'minX' => 1297.40, 'minY' => 2142.80, 'minZ' => -89.00, 'maxX' => 1777.30, 'maxY' => 2243.20, 'maxZ' => 110.90],
        ['name' => 'Redsands West', 'minX' => 1377.30, 'minY' => 2243.20, 'minZ' => -89.00, 'maxX' => 1704.50, 'maxY' => 2433.20, 'maxZ' => 110.90],
        ['name' => 'Redsands West', 'minX' => 1704.50, 'minY' => 2243.20, 'minZ' => -89.00, 'maxX' => 1777.30, 'maxY' => 2342.80, 'maxZ' => 110.90],
        ['name' => 'Regular Tom', 'minX' => -405.70, 'minY' => 1712.80, 'minZ' => -3.00, 'maxX' => -276.70, 'maxY' => 1892.70, 'maxZ' => 200.00],
        ['name' => 'Richman', 'minX' => 647.50, 'minY' => -1118.20, 'minZ' => -89.00, 'maxX' => 787.40, 'maxY' => -954.60, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 647.50, 'minY' => -954.60, 'minZ' => -89.00, 'maxX' => 768.60, 'maxY' => -860.60, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 225.10, 'minY' => -1369.60, 'minZ' => -89.00, 'maxX' => 334.50, 'maxY' => -1292.00, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 225.10, 'minY' => -1292.00, 'minZ' => -89.00, 'maxX' => 466.20, 'maxY' => -1235.00, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 72.60, 'minY' => -1404.90, 'minZ' => -89.00, 'maxX' => 225.10, 'maxY' => -1235.00, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 72.60, 'minY' => -1235.00, 'minZ' => -89.00, 'maxX' => 321.30, 'maxY' => -1008.10, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 321.30, 'minY' => -1235.00, 'minZ' => -89.00, 'maxX' => 647.50, 'maxY' => -1044.00, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 321.30, 'minY' => -1044.00, 'minZ' => -89.00, 'maxX' => 647.50, 'maxY' => -860.60, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 321.30, 'minY' => -860.60, 'minZ' => -89.00, 'maxX' => 687.80, 'maxY' => -768.00, 'maxZ' => 110.90],
        ['name' => 'Richman', 'minX' => 321.30, 'minY' => -768.00, 'minZ' => -89.00, 'maxX' => 700.70, 'maxY' => -674.80, 'maxZ' => 110.90],
        ['name' => 'Robada Intersection', 'minX' => -1119.00, 'minY' => 1178.90, 'minZ' => -89.00, 'maxX' => -862.00, 'maxY' => 1351.40, 'maxZ' => 110.90],
        ['name' => 'Roca Escalante', 'minX' => 2237.40, 'minY' => 2202.70, 'minZ' => -89.00, 'maxX' => 2536.40, 'maxY' => 2542.50, 'maxZ' => 110.90],
        ['name' => 'Roca Escalante', 'minX' => 2536.40, 'minY' => 2202.70, 'minZ' => -89.00, 'maxX' => 2625.10, 'maxY' => 2442.50, 'maxZ' => 110.90],
        ['name' => 'Rockshore East', 'minX' => 2537.30, 'minY' => 676.50, 'minZ' => -89.00, 'maxX' => 2902.30, 'maxY' => 943.20, 'maxZ' => 110.90],
        ['name' => 'Rockshore West', 'minX' => 1997.20, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 2377.30, 'maxY' => 823.20, 'maxZ' => 110.90],
        ['name' => 'Rockshore West', 'minX' => 2377.30, 'minY' => 596.30, 'minZ' => -89.00, 'maxX' => 2537.30, 'maxY' => 788.80, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 72.60, 'minY' => -1684.60, 'minZ' => -89.00, 'maxX' => 225.10, 'maxY' => -1544.10, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 72.60, 'minY' => -1544.10, 'minZ' => -89.00, 'maxX' => 225.10, 'maxY' => -1404.90, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 225.10, 'minY' => -1684.60, 'minZ' => -89.00, 'maxX' => 312.80, 'maxY' => -1501.90, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 225.10, 'minY' => -1501.90, 'minZ' => -89.00, 'maxX' => 334.50, 'maxY' => -1369.60, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 334.50, 'minY' => -1501.90, 'minZ' => -89.00, 'maxX' => 422.60, 'maxY' => -1406.00, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 312.80, 'minY' => -1684.60, 'minZ' => -89.00, 'maxX' => 422.60, 'maxY' => -1501.90, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 422.60, 'minY' => -1684.60, 'minZ' => -89.00, 'maxX' => 558.00, 'maxY' => -1570.20, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 558.00, 'minY' => -1684.60, 'minZ' => -89.00, 'maxX' => 647.50, 'maxY' => -1384.90, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 466.20, 'minY' => -1570.20, 'minZ' => -89.00, 'maxX' => 558.00, 'maxY' => -1385.00, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 422.60, 'minY' => -1570.20, 'minZ' => -89.00, 'maxX' => 466.20, 'maxY' => -1406.00, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 466.20, 'minY' => -1385.00, 'minZ' => -89.00, 'maxX' => 647.50, 'maxY' => -1235.00, 'maxZ' => 110.90],
        ['name' => 'Rodeo', 'minX' => 334.50, 'minY' => -1406.00, 'minZ' => -89.00, 'maxX' => 466.20, 'maxY' => -1292.00, 'maxZ' => 110.90],
        ['name' => 'Royal Casino', 'minX' => 2087.30, 'minY' => 1383.20, 'minZ' => -89.00, 'maxX' => 2437.30, 'maxY' => 1543.20, 'maxZ' => 110.90],
        ['name' => 'San Andreas Sound', 'minX' => 2450.30, 'minY' => 385.50, 'minZ' => -100.00, 'maxX' => 2759.20, 'maxY' => 562.30, 'maxZ' => 200.00],
        ['name' => 'Santa Flora', 'minX' => -2741.00, 'minY' => 458.40, 'minZ' => -7.60, 'maxX' => -2533.00, 'maxY' => 793.40, 'maxZ' => 200.00],
        ['name' => 'Santa Maria Beach', 'minX' => 342.60, 'minY' => -2173.20, 'minZ' => -89.00, 'maxX' => 647.70, 'maxY' => -1684.60, 'maxZ' => 110.90],
        ['name' => 'Santa Maria Beach', 'minX' => 72.60, 'minY' => -2173.20, 'minZ' => -89.00, 'maxX' => 342.60, 'maxY' => -1684.60, 'maxZ' => 110.90],
        ['name' => 'Shady Cabin', 'minX' => -1632.80, 'minY' => -2263.40, 'minZ' => -3.00, 'maxX' => -1601.30, 'maxY' => -2231.70, 'maxZ' => 200.00],
        ['name' => 'Shady Creeks', 'minX' => -1820.60, 'minY' => -2643.60, 'minZ' => -8.00, 'maxX' => -1226.70, 'maxY' => -1771.60, 'maxZ' => 200.00],
        ['name' => 'Shady Creeks', 'minX' => -2030.10, 'minY' => -2174.80, 'minZ' => -6.10, 'maxX' => -1820.60, 'maxY' => -1771.60, 'maxZ' => 200.00],
        ['name' => 'Sobell Rail Yards', 'minX' => 2749.90, 'minY' => 1548.90, 'minZ' => -89.00, 'maxX' => 2923.30, 'maxY' => 1937.20, 'maxZ' => 110.90],
        ['name' => 'Spinybed', 'minX' => 2121.40, 'minY' => 2663.10, 'minZ' => -89.00, 'maxX' => 2498.20, 'maxY' => 2861.50, 'maxZ' => 110.90],
        ['name' => 'Starfish Casino', 'minX' => 2437.30, 'minY' => 1783.20, 'minZ' => -89.00, 'maxX' => 2685.10, 'maxY' => 2012.10, 'maxZ' => 110.90],
        ['name' => 'Starfish Casino', 'minX' => 2437.30, 'minY' => 1858.10, 'minZ' => -39.00, 'maxX' => 2495.00, 'maxY' => 1970.80, 'maxZ' => 60.90],
        ['name' => 'Starfish Casino', 'minX' => 2162.30, 'minY' => 1883.20, 'minZ' => -89.00, 'maxX' => 2437.30, 'maxY' => 2012.10, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 1252.30, 'minY' => -1130.80, 'minZ' => -89.00, 'maxX' => 1378.30, 'maxY' => -1026.30, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 1252.30, 'minY' => -1026.30, 'minZ' => -89.00, 'maxX' => 1391.00, 'maxY' => -926.90, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 1252.30, 'minY' => -926.90, 'minZ' => -89.00, 'maxX' => 1357.00, 'maxY' => -910.10, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 952.60, 'minY' => -1130.80, 'minZ' => -89.00, 'maxX' => 1096.40, 'maxY' => -937.10, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 1096.40, 'minY' => -1130.80, 'minZ' => -89.00, 'maxX' => 1252.30, 'maxY' => -1026.30, 'maxZ' => 110.90],
        ['name' => 'Temple', 'minX' => 1096.40, 'minY' => -1026.30, 'minZ' => -89.00, 'maxX' => 1252.30, 'maxY' => -910.10, 'maxZ' => 110.90],
        ['name' => 'The Camels Toe', 'minX' => 2087.30, 'minY' => 1203.20, 'minZ' => -89.00, 'maxX' => 2640.40, 'maxY' => 1383.20, 'maxZ' => 110.90],
        ['name' => 'The Clowns Pocket', 'minX' => 2162.30, 'minY' => 1783.20, 'minZ' => -89.00, 'maxX' => 2437.30, 'maxY' => 1883.20, 'maxZ' => 110.90],
        ['name' => 'The Emerald Isle', 'minX' => 2011.90, 'minY' => 2202.70, 'minZ' => -89.00, 'maxX' => 2237.40, 'maxY' => 2508.20, 'maxZ' => 110.90],
        ['name' => 'The Farm', 'minX' => -1209.60, 'minY' => -1317.10, 'minZ' => 114.90, 'maxX' => -908.10, 'maxY' => -787.30, 'maxZ' => 251.90],
        ['name' => 'The Four Dragons Casino', 'minX' => 1817.30, 'minY' => 863.20, 'minZ' => -89.00, 'maxX' => 2027.30, 'maxY' => 1083.20, 'maxZ' => 110.90],
        ['name' => 'The High Roller', 'minX' => 1817.30, 'minY' => 1283.20, 'minZ' => -89.00, 'maxX' => 2027.30, 'maxY' => 1469.20, 'maxZ' => 110.90],
        ['name' => 'The Mako Span', 'minX' => 1664.60, 'minY' => 401.70, 'minZ' => 0.00, 'maxX' => 1785.10, 'maxY' => 567.20, 'maxZ' => 200.00],
        ['name' => 'The Panopticon', 'minX' => -947.90, 'minY' => -304.30, 'minZ' => -1.10, 'maxX' => -319.60, 'maxY' => 327.00, 'maxZ' => 200.00],
        ['name' => 'The Pink Swan', 'minX' => 1817.30, 'minY' => 1083.20, 'minZ' => -89.00, 'maxX' => 2027.30, 'maxY' => 1283.20, 'maxZ' => 110.90],
        ['name' => 'The Sherman Dam', 'minX' => -968.70, 'minY' => 1929.40, 'minZ' => -3.00, 'maxX' => -481.10, 'maxY' => 2155.20, 'maxZ' => 200.00],
        ['name' => 'The Strip', 'minX' => 2027.40, 'minY' => 863.20, 'minZ' => -89.00, 'maxX' => 2087.30, 'maxY' => 1703.20, 'maxZ' => 110.90],
        ['name' => 'The Strip', 'minX' => 2106.70, 'minY' => 1863.20, 'minZ' => -89.00, 'maxX' => 2162.30, 'maxY' => 2202.70, 'maxZ' => 110.90],
        ['name' => 'The Strip', 'minX' => 2027.40, 'minY' => 1783.20, 'minZ' => -89.00, 'maxX' => 2162.30, 'maxY' => 1863.20, 'maxZ' => 110.90],
        ['name' => 'The Strip', 'minX' => 2027.40, 'minY' => 1703.20, 'minZ' => -89.00, 'maxX' => 2137.40, 'maxY' => 1783.20, 'maxZ' => 110.90],
        ['name' => 'The Visage', 'minX' => 1817.30, 'minY' => 1863.20, 'minZ' => -89.00, 'maxX' => 2106.70, 'maxY' => 2011.80, 'maxZ' => 110.90],
        ['name' => 'The Visage', 'minX' => 1817.30, 'minY' => 1703.20, 'minZ' => -89.00, 'maxX' => 2027.40, 'maxY' => 1863.20, 'maxZ' => 110.90],
        ['name' => 'Unity Station', 'minX' => 1692.60, 'minY' => -1971.80, 'minZ' => -20.40, 'maxX' => 1812.60, 'maxY' => -1932.80, 'maxZ' => 79.50],
        ['name' => 'Valle Ocultado', 'minX' => -936.60, 'minY' => 2611.40, 'minZ' => 2.00, 'maxX' => -715.90, 'maxY' => 2847.90, 'maxZ' => 200.00],
        ['name' => 'Verdant Bluffs', 'minX' => 930.20, 'minY' => -2488.40, 'minZ' => -89.00, 'maxX' => 1249.60, 'maxY' => -2006.70, 'maxZ' => 110.90],
        ['name' => 'Verdant Bluffs', 'minX' => 1073.20, 'minY' => -2006.70, 'minZ' => -89.00, 'maxX' => 1249.60, 'maxY' => -1842.20, 'maxZ' => 110.90],
        ['name' => 'Verdant Bluffs', 'minX' => 1249.60, 'minY' => -2179.20, 'minZ' => -89.00, 'maxX' => 1692.60, 'maxY' => -1842.20, 'maxZ' => 110.90],
        ['name' => 'Verdant Meadows', 'minX' => 37.00, 'minY' => 2337.10, 'minZ' => -3.00, 'maxX' => 435.90, 'maxY' => 2677.90, 'maxZ' => 200.00],
        ['name' => 'Verona Beach', 'minX' => 647.70, 'minY' => -2173.20, 'minZ' => -89.00, 'maxX' => 930.20, 'maxY' => -1804.20, 'maxZ' => 110.90],
        ['name' => 'Verona Beach', 'minX' => 930.20, 'minY' => -2006.70, 'minZ' => -89.00, 'maxX' => 1073.20, 'maxY' => -1804.20, 'maxZ' => 110.90],
        ['name' => 'Verona Beach', 'minX' => 851.40, 'minY' => -1804.20, 'minZ' => -89.00, 'maxX' => 1046.10, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Verona Beach', 'minX' => 1161.50, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1323.90, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Verona Beach', 'minX' => 1046.10, 'minY' => -1722.20, 'minZ' => -89.00, 'maxX' => 1161.50, 'maxY' => -1577.50, 'maxZ' => 110.90],
        ['name' => 'Vinewood', 'minX' => 787.40, 'minY' => -1310.20, 'minZ' => -89.00, 'maxX' => 952.60, 'maxY' => -1130.80, 'maxZ' => 110.90],
        ['name' => 'Vinewood', 'minX' => 787.40, 'minY' => -1130.80, 'minZ' => -89.00, 'maxX' => 952.60, 'maxY' => -954.60, 'maxZ' => 110.90],
        ['name' => 'Vinewood', 'minX' => 647.50, 'minY' => -1227.20, 'minZ' => -89.00, 'maxX' => 787.40, 'maxY' => -1118.20, 'maxZ' => 110.90],
        ['name' => 'Vinewood', 'minX' => 647.70, 'minY' => -1416.20, 'minZ' => -89.00, 'maxX' => 787.40, 'maxY' => -1227.20, 'maxZ' => 110.90],
        ['name' => 'Whitewood Estates', 'minX' => 883.30, 'minY' => 1726.20, 'minZ' => -89.00, 'maxX' => 1098.30, 'maxY' => 2507.20, 'maxZ' => 110.90],
        ['name' => 'Whitewood Estates', 'minX' => 1098.30, 'minY' => 1726.20, 'minZ' => -89.00, 'maxX' => 1197.30, 'maxY' => 2243.20, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 1970.60, 'minY' => -2179.20, 'minZ' => -89.00, 'maxX' => 2089.00, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2089.00, 'minY' => -2235.80, 'minZ' => -89.00, 'maxX' => 2201.80, 'maxY' => -1989.90, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2089.00, 'minY' => -1989.90, 'minZ' => -89.00, 'maxX' => 2324.00, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2201.80, 'minY' => -2095.00, 'minZ' => -89.00, 'maxX' => 2324.00, 'maxY' => -1989.90, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2541.70, 'minY' => -1941.40, 'minZ' => -89.00, 'maxX' => 2703.50, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2324.00, 'minY' => -2059.20, 'minZ' => -89.00, 'maxX' => 2541.70, 'maxY' => -1852.80, 'maxZ' => 110.90],
        ['name' => 'Willowfield', 'minX' => 2541.70, 'minY' => -2059.20, 'minZ' => -89.00, 'maxX' => 2703.50, 'maxY' => -1941.40, 'maxZ' => 110.90],
        ['name' => 'Yellow Bell Station', 'minX' => 1377.40, 'minY' => 2600.40, 'minZ' => -21.90, 'maxX' => 1492.40, 'maxY' => 2687.30, 'maxZ' => 78.00],
        ['name' => 'Los Santos', 'minX' => 44.60, 'minY' => -2892.90, 'minZ' => -242.90, 'maxX' => 2997.00, 'maxY' => -768.00, 'maxZ' => 900.00],
        ['name' => 'Las Venturas', 'minX' => 869.40, 'minY' => 596.30, 'minZ' => -242.90, 'maxX' => 2997.00, 'maxY' => 2993.80, 'maxZ' => 900.00],
        ['name' => 'Bone County', 'minX' => -480.50, 'minY' => 596.30, 'minZ' => -242.90, 'maxX' => 869.40, 'maxY' => 2993.80, 'maxZ' => 900.00],
        ['name' => 'Tierra Robada', 'minX' => -2997.40, 'minY' => 1659.60, 'minZ' => -242.90, 'maxX' => -480.50, 'maxY' => 2993.80, 'maxZ' => 900.00],
        ['name' => 'Tierra Robada', 'minX' => -1213.90, 'minY' => 596.30, 'minZ' => -242.90, 'maxX' => -480.50, 'maxY' => 1659.60, 'maxZ' => 900.00],
        ['name' => 'San Fierro', 'minX' => -2997.40, 'minY' => -1115.50, 'minZ' => -242.90, 'maxX' => -1213.90, 'maxY' => 1659.60, 'maxZ' => 900.00],
        ['name' => 'Los Santos County', 'minX' => -1213.90, 'minY' => -768.00, 'minZ' => -242.90, 'maxX' => 2997.00, 'maxY' => 596.30, 'maxZ' => 900.00],
        ['name' => 'Flint County', 'minX' => -1213.90, 'minY' => -2892.90, 'minZ' => -242.90, 'maxX' => 44.60, 'maxY' => -768.00, 'maxZ' => 900.00],
        ['name' => 'Whetstone', 'minX' => -2997.40, 'minY' => -2892.90, 'minZ' => -242.90, 'maxX' => -1213.90, 'maxY' => -1115.50, 'maxZ' => 900.00],
    ];

    public function getZoneName(float $x, float $y, float $z): string
    {
        foreach ($this->zones as $zone) {
            if (
                $x >= $zone['minX'] && $x <= $zone['maxX'] &&
                $y >= $zone['minY'] && $y <= $zone['maxY'] &&
                $z >= $zone['minZ'] && $z <= $zone['maxZ']
            ) {
                return $zone['name'];
            }
        }

        return 'Unknown';
    }
}
