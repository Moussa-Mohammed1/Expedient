<?php

namespace App\Http\Middleware;

use App\Models\SuspendedUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class notSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (SuspendedUser::where('user_id', auth()->id())->where('status', 'active')->exists()) {
            return redirect()->route('suspended.show', auth()->user());
        }
        return $next($request);
    }
}
