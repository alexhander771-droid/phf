<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CryptoConfirmController extends Controller
{
  public function confirm(Request $request, string $orderId)
  {
    $data = $request->validate([
      'txid' => ['nullable', 'string', 'max:200'],
      'operator_id' => ['nullable', 'integer'],
    ]);

    $payment = Payment::where('order_id', $orderId)->firstOrFail();

    if ($payment->method !== 'crypto') {
      return response()->json(['message' => 'Not a crypto payment'], 422);
    }

    DB::transaction(function () use ($payment, $data) {
      $payment->refresh();

      // если уже подтвержден — ничего не делаем
      if ($payment->status === 'paid') {
        return;
      }

      // ставим paid
      $payment->status = 'paid';
      $payment->txid = $data['txid'] ?? $payment->txid;
      $payment->confirmed_by = $data['operator_id'] ?? $payment->confirmed_by;
      $payment->confirmed_at = now();
      $payment->completed_at = now();

      // списание USDT с оператора = сумма сделки (для USDT)
      if ($payment->currency === 'USDT' && $payment->amount_dec !== null) {
        $payment->operator_debit_usdt = $payment->amount_dec;

        // комиссия оператора 14% (фиксируем)
        $percent = $payment->operator_fee_percent ?? 14.00;
        $payment->operator_fee_percent = $percent;

        // fee = amount * 0.14
        $payment->operator_fee_usdt = bcmul((string) $payment->amount_dec, '0.14', 6);
      }

      $payment->save();

      // Позже здесь будет: отправка callback на сайт игры (начисление баланса)
    });

    return response()->json(['message' => 'confirmed', 'order_id' => $payment->order_id]);
  }
}
