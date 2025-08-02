<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    /**
     * Show the landing page.
     */
    public function main()
    {
        // Get all services
        $packages = DB::table('packages')->get();

        // Get one featured service (if any)
        $featuredPackage = DB::table('packages')
                            ->where('package_featured', 1)
                            ->first();

        // Return the view with data
        
        return view('main', compact('packages', 'featuredPackage'));
    }
}
