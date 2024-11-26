<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;
use Nowakowskir\JWT\Exceptions\TokenInactiveException;
use Nowakowskir\JWT\Exceptions\IntegrityViolationException;
use Nowakowskir\JWT\Exceptions\AlgorithmMismatchException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Extract the token from the Authorization header.
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $privateKey = env('JWT_PRIVATE_KEY');


            // Validate the token.
            $tokenEncoded = new TokenEncoded($token);
            $tokenEncoded->validate($privateKey, JWT::ALGORITHM_HS256);

            // Decode the token payload
            $payload = $tokenEncoded->decode()->getPayload();

            // Attach payload to the request for use in controllers
            $request->attributes->set('jwt_payload', $payload);
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token has expired'], 401);
        } catch (TokenInactiveException $e) {
            return response()->json(['message' => 'Token is not yet active'], 401);
        } catch (IntegrityViolationException | AlgorithmMismatchException $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

    

}
