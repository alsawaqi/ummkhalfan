<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, \Closure $next): Response
    {
        try {
            // Check if the token is valid (not expired and existing)
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Try to refresh the token if it's expired
            try {
                $token = JWTAuth::parseToken()->refresh();
                // Set the new token on the request for subsequent middleware
                $request->headers->set('Authorization', 'Bearer '.$token);
            } catch (JWTException $e) {
                // If the refresh also fails, catch the exception and respond accordingly
                return response()->json(['error' => 'Token cannot be refreshed, please login again'], 401);
            }
        } catch (JWTException $e) {
            // If there's any other issue with the token, return an error response
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        // If the token is valid or has been refreshed, pass the request further
        return $next($request);
    }
}
