<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        // Validate the incoming request
        $validator = Validator::make($req->all(), [
            'name' => 'required|regex:/^[\pL\s-]+$/u|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'sometimes|in:admin,user',
            'phone' => 'required|string|min:8|max:15|regex:/^[0-9]+$/', // Phone number required
            'address' => 'required|string|min:5|max:255', // Address required
        ]);



        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Assign default role if not provided
        $role = $req->input('role', 'user');

        //dd($req->all());
        // Create the user
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'role' => $role,
            'phone' => $req->phone, // Save phone number if provided
            'address' => $req->address, // Save address if provided
        ]);

        // Generate JWT token
        $token = JWTAuth::fromUser($user);

        // Return response with phone and address included
        return response()->json([
            'message' => 'User successfully registered',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone, // Include phone if it exists
                'address' => $user->address, // Include address if it exists
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'token' => $token,
        ], 201);
    }





    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function direct()
    {
        $type = JWTAuth::user()->role;

        if ($type == 'user') {
            return view('welcome');
        } else {
            return view('welcome');
        }
    }

    public function me()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'No authenticated user found'], 404);
        }

        // Return user details with phone & address
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'address' => $user->address,
            'created_at' => $user->created_at,
        ], 200);
    }

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
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
