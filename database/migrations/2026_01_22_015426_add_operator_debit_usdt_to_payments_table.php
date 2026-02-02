<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->decimal('operator_debit_usdt', 18, 6)->nullable()->after('operator_fee_usdt');
    });
  }

  public function down(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->dropColumn('operator_debit_usdt');
    });
  }
};
