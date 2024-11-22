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
    public function login(Request $request)
    {
        // Validate user credentials and find the user.
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate JWT token
        $privateKey = file_get_contents(config('jwt.private_key'));
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'exp' => time() + (60 * 60), // Token expires in 1 hour
        ];

        $tokenDecoded = new TokenDecoded($payload);
        $token = $tokenDecoded->encode($privateKey, JWT::ALGORITHM_RS256)->toString();

        return response()->json(['token' => $token], 200);
    }

    public function profile(Request $request)
    {
        $payload = $request->attributes->get('jwt_payload');
        return response()->json(['payload' => $payload]);
    }
}
