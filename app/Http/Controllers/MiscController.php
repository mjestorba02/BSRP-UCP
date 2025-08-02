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
}
