<?php

namespace App\Http\Middleware\Api\V1;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecretSiteToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if siteToken is provided, otherwise return 400 Bad Request
        if (!$request->has('siteToken')) {
            return response()->json([
                'success' => false,
                'message' => 'Missing site token',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validate that the provided token matches the secret token
        if ($request->siteToken !== getSecretToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Allow the request to continue if valid
        return $next($request);
    }
}
