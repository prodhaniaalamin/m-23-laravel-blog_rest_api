<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request){
       $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
                'status' => 422
            ], 422);
           
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        $tokens = $user->createToken('123456')->plainTextToken;
       
        return response()->json([
            'user' => $user,
            'message' => 'User created successfully',
            'status' => 201,
            'token' => $tokens
        ], 201);
        // return response()->json(['user' => $user], 201);
    }





    public function login(request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed',
                'status' => 422
            ], 422);
           
        }

        // Check if the user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => 404
            ], 404);
        }

        // Check if the password is correct
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'status' => 401
            ], 401);
        }

        // Create a new token for the user
        $token = $user->createToken('123456')->plainTextToken;

        return response()->json([
            'user' => $user,
            'message' => 'Login successful',
            'status' => 200,
            'token' => $token
        ], 200);
    }




    public function logout()
    {
       Auth::user()->currentAccessToken()->delete();
       
        return response()->json([
            'message' => 'Logout successful',
            'status' => 200
        ], 200);
    }
}
