<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    public function logs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logs = DB::table('log_admin')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function banlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logs = DB::table('log_admin')
            ->where('description', 'like', '%banned%')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.banlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }
}
