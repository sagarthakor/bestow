<?php

namespace App\Http\Middleware;

use App\salesman;
use Closure;
use Illuminate\Http\Request;

class SalesmanApiAuth
{
    /**
     * Resolves the Bearer token to a salesman row and binds it onto the
     * request so controllers don't each re-query it, instead of a
     * session/guard - the mobile app authenticates with a plain token
     * stored on the salesman row (see api_token column), matching this
     * codebase's other hand-rolled auth mechanisms rather than Sanctum.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $salesman = salesman::where('api_token', $token)->first();

        if (empty($salesman)) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $request->attributes->set('salesman', $salesman);

        return $next($request);
    }
}
