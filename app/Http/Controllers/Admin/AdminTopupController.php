<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatorBalance;
use App\Models\OperatorTopup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTopupController extends Controller
{
  public function index(Request $request)
  {
    $status = (string) $request->query('status', 'all');

    $q = OperatorTopup::query()->orderByDesc('id');

    if ($status !== 'all') {
      $q->where('status', $status);
    }

    return response()->json([
      'topups' => $q->paginate(25)->withQueryString(),
    ]);
  }

  public function approve(Request $request, int $id)
  {
    $data = $request->validate([
      'admin_comment' => ['nullable', 'string', 'max:500'],
      'admin_id' => ['nullable', 'integer'],
    ]);

    DB::transaction(function () use ($id, $data) {
      $topup = OperatorTopup::lockForUpdate()->findOrFail($id);

      if ($topup->status !== 'pending') {
        return;
      }

      $topup->status = 'approved';
      $topup->admin_comment = $data['admin_comment'] ?? null;
      $topup->approved_at = now();
      $topup->approved_by = $data['admin_id'] ?? null;
      $topup->save();

      $bal = OperatorBalance::lockForUpdate()->firstOrCreate(
        ['operator_id' => $topup->operator_id],
        ['balance_usdt' => 0]
      );

      $bal->balance_usdt = bcadd((string)$bal->balance_usdt, (string)$topup->amount_usdt, 6);
      $bal->save();
    });

    return response()->json(['message' => 'approved']);
  }

  public function reject(Request $request, int $id)
  {
    $data = $request->validate([
      'admin_comment' => ['nullable', 'string', 'max:500'],
      'admin_id' => ['nullable', 'integer'],
    ]);

    DB::transaction(function () use ($id, $data) {
      $topup = OperatorTopup::lockForUpdate()->findOrFail($id);

      if ($topup->status !== 'pending') {
        return;
      }

      $topup->status = 'rejected';
      $topup->admin_comment = $data['admin_comment'] ?? null;
      $topup->approved_at = now();
      $topup->approved_by = $data['admin_id'] ?? null;
      $topup->save();
    });

    return response()->json(['message' => 'rejected']);
  }
}
