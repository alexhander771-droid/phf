<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorBalance extends Model
{
  protected $fillable = ['operator_id', 'balance_usdt'];
}
