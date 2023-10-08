<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MobileUsersRegistrationController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Register failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        
        DB::beginTransaction();
        try {
            $user = new User;
            $user->name = $request->fullname;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = "user";
            
            $user->save();
            $token = $user->createToken('MyToken')->accessToken;

            DB::commit();
        
            return response()->json([
                "message" => "success",
                "user" => $user,
                "token" => $token
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
            ], 422);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('MyToken')->accessToken;
            

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }
        
    }
    public function logout(Request $request)
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
