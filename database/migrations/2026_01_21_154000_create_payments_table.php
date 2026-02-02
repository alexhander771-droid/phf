<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->id();

      $table->uuid('order_id')->unique();          // наш публичный идентификатор
      $table->unsignedBigInteger('user_id');       // user_id с сайта игры (внешняя ссылка)

      $table->enum('method', ['card', 'crypto']);
      $table->string('provider')->nullable();      // например: tinkoff, sber, yookassa, manual_trc20

      $table->string('status')->default('pending'); // pending/paid/failed/canceled/expired
      $table->boolean('credited')->default(false);  // чтобы не “засчитать” дважды

      // суммы: для RUB лучше хранить в копейках (int), для USDT — decimal
      $table->unsignedBigInteger('amount_int')->nullable(); // копейки для RUB
      $table->decimal('amount_dec', 18, 6)->nullable();     // для USDT
      $table->string('currency', 10);                       // RUB/USDT

      // card
      $table->string('provider_payment_id')->nullable()->index();
      $table->text('pay_url')->nullable();

      // crypto (USDT TRC20)
      $table->string('crypto_network')->nullable(); // TRC20
      $table->string('crypto_address')->nullable();
      $table->string('txid')->nullable()->index();

      // ручное подтверждение оператором (позже добавим operators/admins)
      $table->unsignedBigInteger('confirmed_by')->nullable();
      $table->timestamp('confirmed_at')->nullable();

      $table->json('meta')->nullable(); // любые сырьевые данные (webhook payload и т.п.)
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('payments');
  }
};
