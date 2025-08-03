<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showMainPage()
    {
        if (!Auth::check()) {
            return redirect('/login')->withErrors(['auth' => 'Not logged in']);
        }

        $user = Auth::user()->load(['gang', 'gangrank']);

        // Get total card balance
        $cardTotal = DB::table('cards')
            ->where('owner', $user->uid) // assuming 'uid' is the user's primary key
            ->sum('balance');

        // Calculate total wealth
        $wealth = $user->bank + $user->cash + $cardTotal;

        $gangs = DB::table('gangs')->get();
        $gangranks = DB::table('gangranks')->get();
        $announcement = Cache::get('latest_announcement', 'No announcement yet.');

        return view('dashboard', [
            'mainContent' => 'content.dashboard',
            'gangranks' => $gangranks,
            'gangs' => $gangs,
            'user' => $user,
            'wealth' => $wealth,
            'cardTotal' => $cardTotal,
            'announcement' => $announcement,
        ]);
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username is not registered!'])->withInput();
        }

        // 🔒 Use Whirlpool hash to compare
        $inputHash = strtoupper(hash('whirlpool', $request->password));

        if ($user->password !== $inputHash) {
            return back()->withErrors(['password' => 'Incorrect password!'])->withInput();
        }

        // ✅ If matched, log in the user
        Auth::login($user);

        session([
            'username' => $user->username,
            'admin' => $user->admin,
        ]);
        \Log::info('Logged in user admin level: ' . Auth::user()->admin);
        return redirect('dashboard')->with('success', 'Login successful!');
    }


    public function logout(Request $request)
    {
        Auth::logout();                      // Logs the user out
        $request->session()->invalidate();  // Invalidate the session
        $request->session()->regenerateToken(); // Prevent CSRF reuse

        return redirect('/login');
    }
}