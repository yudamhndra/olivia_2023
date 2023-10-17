<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiController;


class MobileUsersRegistrationController extends ApiController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users'],
            'username' => ['required', 'unique:users'],
            'fullname' => ['required'],
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

             // Get the access token
            $accessToken = $token->accessToken;

            // Save the access token in the user model
            $user->access_token = $accessToken;

            DB::commit();

            return $this->sendSuccessResponse($user, 'Register Success');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendErrorResponse('Registration failed');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendErrorResponse('Login Failed', $validator->errors(), 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('MyToken')->accessToken;

             // Get the access token
             $accessToken = $token->accessToken;

             // Save the access token in the user model
             $user->access_token = $accessToken;

            return $this->sendSuccessResponse($user, 'Login successful');
        } else {
            return $this->sendErrorResponse('Invalid email or password');
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
