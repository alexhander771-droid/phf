<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthTokenController;

use App\Http\Controllers\Operator\DashboardController;
use App\Http\Controllers\Operator\OrdersController;
use App\Http\Controllers\Operator\RapiraController;
use App\Http\Controllers\Operator\TopupController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTopupController;
use App\Http\Controllers\Admin\RequisitesController;

Route::get('/', function () {
  return view('welcome');
});

// login/logout by token
Route::post('/login', [AuthTokenController::class, 'login']);
Route::post('/logout', [AuthTokenController::class, 'logout']);

// OPERATOR (requires token role=operator)
Route::prefix('operator')->middleware('role:operator')->group(function () {
  Route::get('/', [DashboardController::class, 'index']);

  // deals
  Route::get('/orders', [OrdersController::class, 'index']);

  // rate + operator balance
  Route::get('/summary', [RapiraController::class, 'summary']);

  // operator topups
  Route::get('/topups', [TopupController::class, 'index']);
  Route::post('/topups', [TopupController::class, 'store']);
});

// ADMIN (requires token role=admin)
Route::prefix('admin')->middleware('role:admin')->group(function () {
  Route::get('/dashboard', [AdminDashboardController::class, 'index']);

  // approve/reject topups
  Route::get('/topups', [AdminTopupController::class, 'index']);
  Route::post('/topups/{id}/approve', [AdminTopupController::class, 'approve']);
  Route::post('/topups/{id}/reject', [AdminTopupController::class, 'reject']);

  // deposit requisites
  Route::get('/requisites', [RequisitesController::class, 'index']);
  Route::post('/requisites', [RequisitesController::class, 'store']);
  Route::post('/requisites/{id}/toggle', [RequisitesController::class, 'toggle']);
});
