<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    /**
     * Register a new user and return a JWT.
     */
    // public function register(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     // Create the user
    //     $user = User::create([
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'password' => Hash::make($validatedData['password']),
    //     ]);

    //     // Create JWT for the user
    //     $token = $this->createJwtToken();

    //     return response()->json([
    //         'user' => $user,
    //         'token' => $token->getData()->token
    //     ], 201);
    // }

    // /**
    //  * Authenticate a user and return a JWT.
    //  */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Validate user credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Create JWT for the user
        $token = $this->createJwtToken();

        return response()->json([
            'user' => $user,
            'token' => $token->getData()->token
        ], 200);
    }

    // /**
    //  * Invalidate the JWT (simulated logout).
    //  */
    // public function logout(Request $request)
    // {
    //     // Simulate logout by returning a message (JWTs are stateless)
    //     return response()->json(['message' => 'Logged out successfully. Please discard your token.'], 200);
    // }
}
