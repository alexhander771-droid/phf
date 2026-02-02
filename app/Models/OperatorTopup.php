<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorTopup extends Model
{
  protected $fillable = [
    'operator_id',
    'amount_usdt',
    'status',
    'operator_comment',
    'admin_comment',
    'proof_url',
    'approved_at',
    'approved_by'
  ];
}
