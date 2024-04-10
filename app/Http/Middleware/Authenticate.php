<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

final class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guard('jwt')->check()) {
            $user = $this->auth->guard('jwt')->user();
            $request->merge(['user' => $user]);

            $request->setUserResolver(function () use ($user) {
                return $user;
            });

            return $next($request);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($guards[0] === 'jwt') {
            throw new AuthenticationException('Unauthorized.', $guards, $this->redirectTo($request));
        }

        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
