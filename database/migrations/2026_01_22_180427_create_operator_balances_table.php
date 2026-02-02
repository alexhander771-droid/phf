<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('operator_balances', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('operator_id')->unique();
      $table->decimal('balance_usdt', 18, 6)->default(0);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('operator_balances');
  }
};
