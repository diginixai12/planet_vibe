<?php

namespace App\Http\Controllers\API;

use DB;
use Auth;
use JWTAuth;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message'=>'validation error', 'error'=>$validator->errors()], 422);
        }
        $temp_array = [
                'name' => $request->first_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => 1,
            ];
        $user = User::create($temp_array);
        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json(['response' => true, 'message'=>'success', 'token'=>$token], 200);
        } else {
            return response()->json(['response' => false, 'message'=>'failure'], 401);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['response' => false, 'message'=>'validation error', 'error'=>$validator->errors()], 422);
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
            return response()->json(['response' => true, 'message'=>'success', 'token'=>$token], 200);
        }
        else{
            return response()->json(['response' => false, 'message'=>'failure'], 401);
        }
    }
}
