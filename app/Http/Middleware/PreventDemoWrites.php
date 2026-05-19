<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDemoWrites
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isDemoAccount() || $request->isMethodSafe() || $request->routeIs('logout')) {
            return $next($request);
        }

        $message = 'Le compte démo est en lecture seule. Crée ton propre compte pour modifier les données.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 423);
        }

        return redirect()->back()->with('status', $message);
    }
}
