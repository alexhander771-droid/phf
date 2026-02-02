<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $fillable = [
    'order_id',
    'user_id',
    'method',
    'provider',
    'status',
    'credited',
    'amount_int',
    'amount_dec',
    'currency',
    'provider_payment_id',
    'pay_url',
    'crypto_network',
    'crypto_address',
    'txid',
    'confirmed_by',
    'confirmed_at',
    'meta',
  ];

  protected $casts = [
    'credited' => 'boolean',
    'confirmed_at' => 'datetime',
    'meta' => 'array',
  ];
}
