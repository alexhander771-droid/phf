<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\OperatorBalance;
use App\Services\RateService;

class RapiraController extends Controller
{
  public function summary(RateService $rateService)
  {
    $rate = $rateService->usdtRub();

    // TODO: когда будет авторизация — заменить на auth()->id()
    $operatorId = 1;

    $balance = OperatorBalance::where('operator_id', $operatorId)->value('balance_usdt');
    if ($balance === null) {
      $balance = 0;
    }

    return response()->json([
      'operator_usdt_balance' => number_format((float) $balance, 6, '.', ''),
      'usdt_rub_rate' => $rate !== null ? number_format($rate, 2, '.', '') : null,
      'source' => $rate !== null ? 'coingecko' : null,
      'updated_at' => now()->toIso8601String(),
    ]);
  }
}
