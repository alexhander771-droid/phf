<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('deposit_requisites', function (Blueprint $table) {
      $table->id();
      $table->string('title'); // например "USDT TRC20"
      $table->string('network')->nullable(); // TRC20/ERC20/etc
      $table->text('details'); // адрес/кошелек/банк/коммент
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('deposit_requisites');
  }
};
