<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentsController extends Controller
{
  public function store(Request $request)
  {
    $data = $request->validate([
      'user_id' => ['required', 'integer', 'min:1'],
      'method' => ['required', 'in:card,crypto'],
      'currency' => ['required', 'in:RUB,USDT'],
      'amount' => ['required'], // валидируем ниже в зависимости от currency/method
    ]);

    $orderId = (string) Str::uuid();

    if ($data['currency'] === 'RUB') {
      // ожидаем рубли, сохраняем в копейках
      $rub = (int) $data['amount'];
      if ($rub < 10) {
        return response()->json(['message' => 'min amount is 10 RUB'], 422);
      }
      $amountInt = $rub * 100;
      $amountDec = null;
    } else {
      // USDT (decimal строкой)
      $usdt = (string) $data['amount'];
      $amountDec = $usdt;
      $amountInt = null;
    }

    $payment = Payment::create([
      'order_id' => $orderId,
      'user_id' => (int) $data['user_id'],
      'method' => $data['method'],
      'provider' => $data['method'] === 'crypto' ? 'manual_trc20' : null,
      'status' => 'pending',
      'currency' => $data['currency'],
      'amount_int' => $amountInt,
      'amount_dec' => $amountDec,
      'crypto_network' => $data['method'] === 'crypto' ? 'TRC20' : null,
      // crypto_address позже: либо один адрес из .env, либо пул адресов
    ]);

    // Пока: для crypto вернём реквизиты; для card — заглушка (позже подключим эквайринг)
    if ($payment->method === 'crypto') {
      $address = config('payments.usdt_trc20_address');
      $payment->crypto_address = $address;
      $payment->save();

      return response()->json([
        'order_id' => $payment->order_id,
        'status' => $payment->status,
        'method' => $payment->method,
        'currency' => $payment->currency,
        'amount' => $payment->amount_dec,
        'details' => [
          'network' => 'TRC20',
          'address' => $payment->crypto_address,
        ],
      ]);
    }

    return response()->json([
      'order_id' => $payment->order_id,
      'status' => $payment->status,
      'method' => $payment->method,
      'message' => 'Card provider not connected yet',
    ], 501);
  }

  public function show(string $orderId)
  {
    $payment = Payment::where('order_id', $orderId)->firstOrFail();

    return response()->json([
      'order_id' => $payment->order_id,
      'user_id' => $payment->user_id,
      'method' => $payment->method,
      'currency' => $payment->currency,
      'status' => $payment->status,
      'amount_int' => $payment->amount_int,
      'amount_dec' => $payment->amount_dec,
    ]);
  }
}
