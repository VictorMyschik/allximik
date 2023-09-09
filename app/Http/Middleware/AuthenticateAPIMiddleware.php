<?php

namespace App\Http\Middleware;

use App\Exceptions\APIAuthException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAPIMiddleware
{
  public const GUARD = 'jwt';

  public function handle(Request $request, Closure $next): Response
  {
    if (!Auth::guard(self::GUARD)->check()) {
      throw new APIAuthException('Unauthorized');
    }

    return $next($request);
  }
}
