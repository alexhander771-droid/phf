<?php

use App\Http\Controllers\Admin\CryptoConfirmController;
use App\Http\Controllers\Api\PaymentsController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/payments', [PaymentsController::class, 'store']);
Route::get('/v1/payments/{orderId}', [PaymentsController::class, 'show']);


Route::post('/v1/admin/crypto/{orderId}/confirm', [CryptoConfirmController::class, 'confirm']);
