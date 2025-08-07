<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Updates;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

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

        $announcements = Announcement::latest()->get();
        $updates = Updates::latest()->get();

        // Setup Markdown environment and parser
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'commonmark' => [
                'enable_em' => false,
                'enable_strong' => true,
                'hard_breaks' => false,
            ],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());

        $converter = new CommonMarkConverter([], $environment);

        // Parse each announcement and update field using Markdown
        foreach ($announcements as $announcement) {
            $announcement->message = $converter->convertToHtml($announcement->message);
        }

        foreach ($updates as $update) {
            $update->updates = $converter->convertToHtml($update->updates);
        }

        return view('dashboard', [
            'mainContent' => 'content.dashboard',
            'gangranks' => $gangranks,
            'gangs' => $gangs,
            'user' => $user,
            'wealth' => $wealth,
            'cardTotal' => $cardTotal,
            'announcements' => $announcements,
            'updates' => $updates,
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

        return redirect('login');
    }

    public function destroy_announcement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }

    public function destroy_updates($id)
    {
        $update = Updates::findOrFail($id);
        $update->delete();

        return back()->with('success', 'Updates deleted successfully.');
    }
}