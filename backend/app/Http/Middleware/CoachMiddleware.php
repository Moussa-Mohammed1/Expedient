<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CoachMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Gate::any(['coach-access', 'admin-access'])) {
            return redirect('/home');
        }

        return $next($request);
    }
}