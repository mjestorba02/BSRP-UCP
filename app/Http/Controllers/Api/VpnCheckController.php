<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VpnController extends Controller
{
    // Check if IP is VPN
    public function check($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return response()->json(['error' => 'Invalid IP address'], 400);
        }

        $ipLong = sprintf("%u", ip2long($ip)); // unsigned

        $entry = DB::table('antivpn_entries')
            ->where('start_ip', '<=', $ipLong)
            ->where('end_ip', '>=', $ipLong)
            ->first();

        if ($entry) {
            return response()->json([
                'ip' => $ip,
                'isVPN' => true,
                'type' => $entry->type,
                'source' => $entry->source
            ]);
        }

        return response()->json([
            'ip' => $ip,
            'isVPN' => false
        ]);
    }

    // Health check
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'now' => now()->toDateTimeString()
        ]);
    }

    // Refresh VPN lists (Node.js cron equivalent)
    public function refresh()
    {
        $sources = [
            'https://raw.githubusercontent.com/X4BNet/lists_vpn/main/output/vpn/ipv4.txt',
            'https://raw.githubusercontent.com/az0/vpn_ip/main/protonvpn.txt',
            'https://raw.githubusercontent.com/X4BNet/lists_vpn/main/ipv4.txt',
            'https://raw.githubusercontent.com/stamparm/ipsum/master/ipsum.txt',
            'https://raw.githubusercontent.com/firehol/blocklist-ipsets/master/firehol_vpnnet.ipset',
            'https://www.vpngate.net/api/iphone/',
            'https://raw.githubusercontent.com/17mon/china_ip_list/master/china_ip_list.txt',
            'https://raw.githubusercontent.com/vpnapi/vpnapi-database/master/vpn-ipv4.txt'
        ];

        $total = 0;

        foreach ($sources as $url) {
            try {
                $text = file_get_contents($url);
                if (!$text) continue;

                $ips = $this->parseListText($text);

                foreach ($ips as $ipOrCidr) {
                    $range = $this->cidrToRange($ipOrCidr);
                    if (!$range) continue;

                    [$start, $end] = $range;

                    DB::table('antivpn_entries')->updateOrInsert(
                        ['start_ip' => $start, 'end_ip' => $end],
                        ['type' => 'free', 'source' => $url, 'updated_at' => now()]
                    );
                    $total++;
                }

            } catch (\Exception $e) {
                \Log::error("Failed fetching $url: " . $e->getMessage());
            }
        }

        return response()->json(['upserted' => $total]);
    }

    private function parseListText($text)
    {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $ips = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line || str_starts_with($line, '#') || str_starts_with($line, ';')) continue;

            if (preg_match_all('/\d{1,3}(?:\.\d{1,3}){3}(?:\/\d{1,2})?/', $line, $matches)) {
                foreach ($matches[0] as $m) $ips[] = $m;
            }
        }

        return array_unique($ips);
    }

    private function cidrToRange($cidr)
    {
        $cidr = trim($cidr);
        if (!str_contains($cidr, '/')) {
            $n = ip2long($cidr);
            return $n === false ? null : [$n, $n];
        }

        [$ip, $prefix] = explode('/', $cidr);
        $prefix = (int)$prefix;
        if ($prefix < 0 || $prefix > 32) return null;

        $start = ip2long($ip) & (~((1 << (32 - $prefix)) - 1));
        $end = $start + pow(2, (32 - $prefix)) - 1;

        return [$start, $end];
    }
}