<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || !Partner::where('api_key', $apiKey)->exists()) {
            return response()->json(['error' => 'Invalid API key'], 403);
        }

        return $next($request);
    }
}
