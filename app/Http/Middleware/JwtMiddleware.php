<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\Exceptions\IntegrityViolationException;
use Nowakowskir\JWT\Exceptions\AlgorithmMismatchException;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;
use Nowakowskir\JWT\Exceptions\TokenInactiveException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken(); // Extract the token from the Authorization header.

        if (!$token) {
            return Response::json(['message' => 'Unauthorized'], 401);
        }

        try {
            $publicKey = file_get_contents(config('jwt.public_key')); // Load public key from config.

            // Validate the token with the public key and algorithm.
            $tokenEncoded = new TokenEncoded($token);
            $tokenEncoded->validate($publicKey, JWT::ALGORITHM_RS256);

            // Pass the decoded payload to the request for further processing.
            $payload = $tokenEncoded->decode()->getPayload();
            $request->attributes->set('jwt_payload', $payload);

        } catch (TokenExpiredException $e) {
            return Response::json(['message' => 'Token has expired'], 401);
        } catch (TokenInactiveException $e) {
            return Response::json(['message' => 'Token is not yet active'], 401);
        } catch (IntegrityViolationException | AlgorithmMismatchException $e) {
            return Response::json(['message' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            return Response::json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
