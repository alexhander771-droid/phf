<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRole
{
  public function handle(Request $request, Closure $next, string $role)
  {
    $sessionRole = $request->session()->get('role');

    if ($sessionRole !== $role) {
      return redirect('/')->with('error', 'Доступ запрещён. Войдите по токену.');
    }

    return $next($request);
  }
}
