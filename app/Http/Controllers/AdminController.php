<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    public function showPublicAdminRoster()
    {
        $user = auth()->user();

        $admins = DB::table('users')
            ->where('adminlevel', '>=', 1)
            ->orderByDesc('adminlevel')
            ->get();

        return view('dashboard', [
            'mainContent' => 'admin.publicroster',
            'admins' => $admins,
            'user' => $user,
        ]);
    }

    public function showAdminRoster()
    {
        $user = auth()->user();

        $admins = DB::table('users')
            ->where('adminlevel', '>=', 1)
            ->orderByDesc('adminlevel')
            ->get();

        foreach ($admins as $admin) {
            $divisions = [];

            if ($admin->factionmod) {
                $divisions[] = 'FM';
            }
            if ($admin->gangmod) {
                $divisions[] = 'GM';
            }
            if ($admin->banappealer) {
                $divisions[] = 'HI';
            }
            if ($admin->publicrelations) {
                $divisions[] = 'PR';
            }
            if ($admin->nongamingstaff) {
                $divisions[] = 'NGS';
            }
            if ($admin->dynamicadmin) {
                $divisions[] = 'DA';
            }

            $admin->division = count($divisions) > 0 ? implode(' | ', $divisions) : 'NONE';
        }

        return view('dashboard', [
            'mainContent' => 'admin.roster',
            'admins' => $admins,
            'user' => $user,
        ]);
    }

    public function getAdminRank(int $adminLevel): string
    {
        return match (true) {
            $adminLevel == 1 => 'Trial Admin',
            $adminLevel == 2 => 'Junior Admin',
            $adminLevel == 3 => 'General Admin',
            $adminLevel == 4 => 'Senior Admin',
            $adminLevel == 5 => 'Head Admin',
            $adminLevel == 6 => 'Executive Admin',
            $adminLevel == 7 => 'Management',
            default                                => 'Unknown',
        };
    }

    public function logs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_admin')->count();

        if ($logCount >= 20000) {
            DB::table('log_admin')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_admin')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.logs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function banlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_admin')->count();

        if ($logCount >= 20000) {
            DB::table('log_admin')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_admin')
            ->where('description', 'like', '%banned%')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.banlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function cmdlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_cmd')->count();

        if ($logCount >= 20000) {
            DB::table('log_cmd')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_cmd')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.cmdlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function admincmdlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_admincmd')->count();

        if ($logCount >= 20000) {
            DB::table('log_admincmd')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_admincmd')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.admincmdlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function quitlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_leave')->count();

        if ($logCount >= 20000) {
            DB::table('log_leave')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_leave')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.quitlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function lootlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_loots')->count();

        if ($logCount >= 20000) {
            DB::table('log_loots')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_loots')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.lootlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }

    public function killlogs(Request $request)
    {
        $user = auth()->user();

        $search = $request->input('search');

        $logCount = DB::table('log_kills')->count();

        if ($logCount >= 20000) {
            DB::table('log_kills')
                ->orderBy('date', 'asc')
                ->limit(1)
                ->delete();
        }

        $logs = DB::table('log_kills')
            ->when($search, function ($query, $search) {
                return $query->where('description', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('dashboard', [
            'mainContent' => 'admin.logs.killlogs',
            'user' => $user,
            'search' => $search,
            'logs' => $logs,
        ]);
    }
}
