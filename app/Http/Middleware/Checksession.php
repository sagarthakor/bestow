<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class Checksession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user=Session::get("user_id");

        if (empty($user)) {
            return redirect("/");
        }
        return $next($request);
    }
}
