<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('operator_topups', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('operator_id');
      $table->decimal('amount_usdt', 18, 6);
      $table->string('status', 20)->default('pending'); // pending|approved|rejected
      $table->text('operator_comment')->nullable();
      $table->text('admin_comment')->nullable();
      $table->string('proof_url')->nullable(); // позже можно прикрутить загрузку чека
      $table->timestamp('approved_at')->nullable();
      $table->unsignedBigInteger('approved_by')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('operator_topups');
  }
};
