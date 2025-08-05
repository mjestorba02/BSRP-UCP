<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Clothing;

class MiscController extends Controller
{
    public function phoneBook(Request $request)
    {
        $user = auth()->user();

        $perPage = 10;
        $page = $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        $entries = DB::table('rp_contacts')
            ->where('Phone', $user->uid)
            ->orderBy('Contact', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $total = DB::table('rp_contacts')->count();

        return view('dashboard', [
            'mainContent' => 'misc.phonebook',
            'entries' => $entries,
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'user' => $user
        ]);
    }

    public function turfs(Request $request)
    {
        $user = auth()->user();

        $perPage = 10;
        $page = $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        $turfs = DB::table('turfs')
            ->leftJoin('gangs', 'turfs.capturedgang', '=', 'gangs.id')
            ->select('turfs.*', 'gangs.name as gang_name', 'gangs.color')
            ->orderBy('turfs.id', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->map(function ($turf) {
                $typeMap = [
                    0 => 'Normal',
                    1 => 'Salestax',
                    2 => 'Materials',
                    3 => 'Drug Factory',
                    4 => 'Cargo Drugs',
                    5 => 'Cargo Materials'
                ];
                $turf->type_label = $typeMap[$turf->type] ?? 'Unknown';
                return $turf;
            });

        $total = DB::table('turfs')->count();

        //Traphouses
        $thperPage = 10;
        $thpage = $request->query('page', 1);
        $thoffset = ($thpage - 1) * $thperPage;

        $thouse = DB::table('traphouse')
            ->orderBy('traphouse.id', 'asc')
            ->offset($thoffset)
            ->limit($perPage)
            ->get();

        $thtotal = DB::table('traphouse')->count();

        return view('dashboard', [
            'mainContent' => 'misc.turfs',
            'thouse' => $thouse,
            'thcurrentPage' => $thpage,
            'thperPage' => $thperPage,
            'thtotal' => $thtotal,
            'turfs' => $turfs,
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'user' => $user,
        ]);
    }

    public function toys()
    {
        $user = auth()->user();

        $clothingItems = Clothing::where('uid', $user->uid)
            ->get();


        return view('dashboard', [
            'mainContent' => 'misc.toys',
            'clothingItems' => $clothingItems,
            'user' => $user,
        ]);
    }

    function getBoneName(int $boneId): ?string
    {
        $bones = [
            0  => 'Spine',
            1  => 'Head',
            2  => 'Left upper arm',
            3  => 'Right upper arm',
            4  => 'Left hand',
            5  => 'Right hand',
            6  => 'Left thigh',
            7  => 'Right thigh',
            8  => 'Left foot',
            9  => 'Right foot',
            10 => 'Right calf',
            11 => 'Left calf',
            12 => 'Left forearm',
            13 => 'Right forearm',
            14 => 'Left shoulder',
            15 => 'Right shoulder',
            16 => 'Neck',
            17 => 'Jaw',
        ];

        return $bones[$boneId] ?? null; // Return null if index doesn't exist
    }
}
