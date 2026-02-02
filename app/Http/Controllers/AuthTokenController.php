<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthTokenController extends Controller
{
  public function login(Request $request)
  {
    $data = $request->validate([
      'token' => ['required', 'string', 'max:200'],
    ]);

    $token = trim($data['token']);

    $operatorToken = (string) env('OPERATOR_TOKEN', '');
    $adminToken = (string) env('ADMIN_TOKEN', '');

    if ($operatorToken !== '' && hash_equals($operatorToken, $token)) {
      $request->session()->put('role', 'operator');
      return redirect('/operator');
    }

    if ($adminToken !== '' && hash_equals($adminToken, $token)) {
      $request->session()->put('role', 'admin');
      return redirect('/admin/dashboard');
    }

    return redirect('/')->with('error', 'Неверный токен');
  }

  public function logout(Request $request)
  {
    $request->session()->forget('role');
    return redirect('/')->with('success', 'Вы вышли');
  }
}
