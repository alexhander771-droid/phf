<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->timestamp('expires_at')->nullable()->index();
      $table->timestamp('completed_at')->nullable()->index();

      $table->decimal('operator_fee_percent', 5, 2)->default(14.00);
      $table->decimal('operator_fee_usdt', 18, 6)->nullable();
    });
  }

  public function down(): void
  {
    Schema::table('payments', function (Blueprint $table) {
      $table->dropColumn([
        'expires_at',
        'completed_at',
        'operator_fee_percent',
        'operator_fee_usdt'
      ]);
    });
  }
};
