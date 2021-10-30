<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

class ApiUserController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'role' => 2
            ]
        ));

        return response()->json([
            'success' => true,
            'message' => 'User successfully registered',
        ], 201);

    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'password' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json([
                'success' =>  false,
                'errors' => $validator->errors(),
            ], 400);
        }

        if (!$token = Auth::attempt($validator->validate())) {
            return response()->json([
                'login' => false,
                'message' => 'These Credentials Doesnt match with our records'
            ], 401);
        }
        return $this->respondWithToken($token);

        // return response()->json([
        //         'success' => true,
        //         'messsge' => 'Success login'
        //     ], 202);
    }
    
    public function logout(Request $request){
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'success Logout'
        ], 202);
    } 

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 1440
        ], 202);
    }
}
