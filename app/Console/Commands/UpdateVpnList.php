<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AntivpnEntry;

class UpdateAntivpnList extends Command
{
    protected $signature = 'antivpn:update-ip-list';
    protected $description = 'Download and update VPN IP list from free sources';

    const FREE_SOURCES = [
        'https://raw.githubusercontent.com/X4BNet/lists_vpn/main/output/vpn/ipv4.txt',
        'https://raw.githubusercontent.com/az0/vpn_ip/main/protonvpn.txt',
        'https://raw.githubusercontent.com/stamparm/ipsum/master/ipsum.txt',
        // Add more...
    ];

    public function handle()
    {
        foreach (self::FREE_SOURCES as $url) {
            $content = @file_get_contents($url);
            if (!$content) {
                $this->error("Failed to download $url");
                continue;
            }

            $ips = $this->parseIPs($content, $url);

            foreach ($ips as $ip) {
                [$startIp, $endIp] = $this->cidrToRange($ip);
                if ($startIp === false || $endIp === false) {
                    $this->error("Invalid IP format: $ip");
                    continue;
                }
                AntivpnEntry::updateOrCreate(
                    ['start_ip' => $startIp, 'end_ip' => $endIp],
                    ['type' => 'free', 'source' => $url]
                );
            }

            $this->info("Updated entries from $url");
        }
    }

    protected function parseIPs(string $content, string $sourceUrl): array
    {
        $lines = explode("\n", $content);
        $ips = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) continue;
            $ips[] = $line;
        }
        return $ips;
    }

    protected function cidrToRange(string $cidr): array
    {
        if (strpos($cidr, '/') === false) {
            $ipNum = ip2long($cidr);
            if ($ipNum === false) return [false, false];
            return [$ipNum, $ipNum];
        }

        list($ip, $mask) = explode('/', $cidr);
        $ipNum = ip2long($ip);
        if ($ipNum === false) return [false, false];
        $mask = (int)$mask;

        $start = $ipNum & (~((1 << (32 - $mask)) - 1));
        $end = $start + pow(2, (32 - $mask)) - 1;
        return [$start, $end];
    }
}