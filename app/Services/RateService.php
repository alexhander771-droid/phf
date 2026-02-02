<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class RateService
{
    public function usdtRub(): ?float
    {
        return Cache::remember('rate:usdt_rub', 30, function () {
            $url = 'https://api.coingecko.com/api/v3/simple/price?ids=tether&vs_currencies=rub';

            $ctx = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 6,
                    'header' => "Accept: application/json\r\nUser-Agent: payments-service\r\n",
                ],
            ]);

            $raw = @file_get_contents($url, false, $ctx);
            if ($raw === false || $raw === '') {
                return null;
            }

            $data = json_decode($raw, true);
            if (!is_array($data)) {
                return null;
            }

            $val = $data['tether']['rub'] ?? null;
            return is_numeric($val) ? (float) $val : null;
        });
    }
}
