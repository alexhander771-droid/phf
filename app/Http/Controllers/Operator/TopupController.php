<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DepositRequisite;
use App\Models\OperatorTopup;
use Illuminate\Http\Request;

class TopupController extends Controller
{
  public function index()
  {
    // Временно operator_id захардкожен (как у тебя в проекте).
    // Потом можно привязать к auth()->id()
    $operatorId = 1;

    return response()->json([
      'requisites' => DepositRequisite::where('is_active', true)->orderByDesc('id')->get(),
      'topups' => OperatorTopup::where('operator_id', $operatorId)->orderByDesc('id')->limit(50)->get(),
    ]);
  }

  public function store(Request $request)
  {
    $operatorId = 1;

    $data = $request->validate([
      'amount_usdt' => ['required', 'numeric', 'min:0.01'],
      'operator_comment' => ['nullable', 'string', 'max:500'],
    ]);

    $topup = OperatorTopup::create([
      'operator_id' => $operatorId,
      'amount_usdt' => $data['amount_usdt'],
      'operator_comment' => $data['operator_comment'] ?? null,
      'status' => 'pending',
    ]);

    return response()->json(['message' => 'created', 'topup' => $topup]);
  }
}
