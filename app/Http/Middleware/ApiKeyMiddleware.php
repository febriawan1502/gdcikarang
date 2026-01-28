<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Validate API key from headers.
     */
    public function handle(Request $request, Closure $next)
    {
        $expected = env('API_KEY');
        $provided = $request->header('X-API-Key') ?? $request->header('Authorization');

        if (is_string($provided) && str_starts_with($provided, 'Bearer ')) {
            $provided = substr($provided, 7);
        }

        if (!$expected || !$provided || !hash_equals($expected, (string) $provided)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
