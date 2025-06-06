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

    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('api_key');
        $partner = Partner::where('api_key', $apiKey)->where('is_active', true)->first();

        if (!$partner) {
            return response()->json(['message' => 'Unauthorized. Invalid or missing API key.'], 401);
        }

        $request->attributes->set('partner', $partner);

        return $next($request);
    }
}
