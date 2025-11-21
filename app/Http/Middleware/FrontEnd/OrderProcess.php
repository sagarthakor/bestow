<?php

namespace App\Http\Middleware\FrontEnd;

use Closure;

class OrderProcess
{
    public function handle($request, Closure $next)
    {
        $current_route = \Route::getCurrentRoute()->getName();

        //dd($current_route);
        if(!\Session::has('user'))
            return redirect()->route('user.login', ['redirect_route' => $current_route]);

        if(!\Session::has('order-checkout'))
            return redirect()->route('website.home')->with(['error' => 'Cart Session is expired, Try Again']);

        return $next($request);
    }
}
