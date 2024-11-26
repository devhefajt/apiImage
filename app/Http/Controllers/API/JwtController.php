<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;


class JwtController extends Controller
{
    /**
     * Create a JWT token with expiration time (10 seconds in this case).
     */
    public function createJwtToken()
    {
        $privateKey = env("JWT_PRIVATE_KEY");

        // Create the token with expiration time (10 seconds in this case)
        $tokenDecoded = new TokenDecoded(['exp' => time() + 15]); 
        $tokenEncoded = $tokenDecoded->encode($privateKey, JWT::ALGORITHM_HS256);

        // Return the JWT as a string
        return response()->json(['token' => $tokenEncoded->toString()]);
    }

   
}
