<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiscController extends Controller
{
    public function phoneBook(Request $request)
    {
        $user = auth()->user();

        $perPage = 10;
        $page = $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        $entries = DB::table('phonebook')
            ->orderBy('name', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        $total = DB::table('phonebook')->count();

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

        $entries = DB::table('turfs')
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

        $entries = DB::table('traphouse')
            ->orderBy('traphouse.id', 'asc')
            ->offset($thoffset)
            ->limit($perPage)
            ->get();

        $total = DB::table('traphouse')->count();

        return view('dashboard', [
            'mainContent' => 'misc.turfs',
            'thouse' => $thouse,
            'thcurrentPage' => $thpage,
            'thperPage' => $thperPage,
            'thtotal' => $thtotal,
            'user' => $user,
        ]);
    }
}
