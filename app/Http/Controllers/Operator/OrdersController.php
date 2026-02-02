<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
  public function index(Request $request)
  {
    // автопросрочка
    Payment::where('status', 'pending')
      ->whereNotNull('expires_at')
      ->where('expires_at', '<', now())
      ->update(['status' => 'expired']);

    $q = trim((string) $request->query('q', ''));
    $perPage = (int) $request->query('per_page', 25);
    $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 25;

    // all | pending | paid | rejected
    $statusFilter = (string) $request->query('status', 'all');
    if (!in_array($statusFilter, ['all', 'pending', 'paid', 'rejected'], true)) {
      $statusFilter = 'all';
    }

    $query = Payment::query()->orderByDesc('id');

    if ($statusFilter === 'pending') {
      $query->where('status', 'pending');
    } elseif ($statusFilter === 'paid') {
      $query->where('status', 'paid');
    } elseif ($statusFilter === 'rejected') {
      $query->whereIn('status', ['failed', 'expired']);
    }

    if ($q !== '') {
      $query->where(function ($sub) use ($q) {
        $sub->where('order_id', 'like', "%{$q}%")
          ->orWhere('user_id', 'like', "%{$q}%")
          ->orWhere('txid', 'like', "%{$q}%")
          ->orWhere('provider_payment_id', 'like', "%{$q}%");
      });
    }

    $paginator = $query->paginate($perPage)->withQueryString();

    $orders = collect($paginator->items())->map(function (Payment $p) {
      $amount = $p->currency === 'USDT'
        ? (string) $p->amount_dec
        : ($p->amount_int ? (string) ($p->amount_int / 100) : null);

      return [
        'order_id' => $p->order_id,
        'user_id' => $p->user_id,
        'method' => $p->method,
        'currency' => $p->currency,
        'amount' => $amount,
        'status' => $p->status,
        'expires_at' => optional($p->expires_at)->toIso8601String(),
        'completed_at' => optional($p->completed_at)->toIso8601String(),
        'operator_fee_percent' => (string) $p->operator_fee_percent,
        'operator_fee_usdt' => $p->operator_fee_usdt !== null ? (string) $p->operator_fee_usdt : null,
        'operator_debit_usdt' => $p->operator_debit_usdt !== null ? (string) $p->operator_debit_usdt : null,
      ];
    });

    $totalFee = Payment::where('status', 'paid')
      ->where('currency', 'USDT')
      ->sum('operator_fee_usdt');

    return response()->json([
      'orders' => $orders,
      'pagination' => [
        'total' => $paginator->total(),
        'per_page' => $paginator->perPage(),
        'current_page' => $paginator->currentPage(),
        'last_page' => $paginator->lastPage(),
      ],
      'total_operator_fee_usdt' => (string) $totalFee,
    ]);
  }
}
