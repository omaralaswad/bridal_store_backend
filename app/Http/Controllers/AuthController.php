<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{

    // Show login form
    // public function showLoginForm()
    // {
    //     return view('auth.login'); // Create a view file for the login form
    // }
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function register(Request $req)
    {
        // Validate the incoming request
        $validator = Validator::make($req->all(), [
            'first_name' => 'required|regex:/^[\pL\s-]+$/u|string',
            'last_name' => 'required|regex:/^[\pL\s-]+$/u|string',
            'age' => 'nullable|integer',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'sometimes|in:admin,user', // Only allow 'admin' or 'user'
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Assign default role to 'user' if not provided
        $role = $req->input('role', 'user');

        // Create the user
        $user = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'age' => $req->age,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'role' => $role, // Use the provided role or default to 'user'
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json(['message' => 'User successfully registered'], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken(token: $token);

    }



    //     public function login(Request $request)
// {
//     $credentials = $request->only('email', 'password');

    //     Log::info('Attempting to log in with credentials:', $credentials);

    //     if (Auth::attempt($credentials)) {
//         Log::info('User logged in successfully: ' . $request->email);
//         return redirect()->intended('/admin/dashboard'); // Ensure this URL matches your dashboard route
//     }

    //     Log::warning('Failed login attempt for email: ' . $request->email);
//     return redirect('login')->withErrors([
//         'email' => 'The provided credentials do not match our records.',
//     ]);
// }



    // Adjusted the 'direct' method to use JWTAuth
    public function direct()
    {
        $type = JWTAuth::user()->type_of_user;

        if ($type == 'user') {
            return view('welcome');
        } else {
            return view('welcome');
        }
    }

    // Adjusted 'me' method to use JWTAuth
    public function me()
    {
        return response()->json(JWTAuth::user());
    }

    // Adjusted 'logout' method to invalidate JWT token
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 // Adjusted TTL
        ]);
    }
}
