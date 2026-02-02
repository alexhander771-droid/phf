<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositRequisite extends Model
{
  protected $fillable = ['title', 'network', 'details', 'is_active'];
}
