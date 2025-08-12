<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AntivpnEntry;
use Illuminate\Support\Facades\Validator;

class AntivpnController extends Controller
{
    public function checkIp($ip)
    {
        // Validate IP address
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return response()->json(['error' => 'Invalid IPv4 address'], 400);
        }

        $ipLong = ip2long($ip);

        $entry = AntivpnEntry::where('start_ip', '<=', $ipLong)
            ->where('end_ip', '>=', $ipLong)
            ->first();

        if ($entry) {
            return response()->json([
                'ip' => $ip,
                'isVPN' => true,
                'type' => $entry->type,
            ]);
        } else {
            return response()->json([
                'ip' => $ip,
                'isVPN' => false,
                'type' => null,
            ]);
        }
    }
}
