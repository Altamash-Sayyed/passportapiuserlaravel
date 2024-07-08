<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //POST  [name,email,password]
    public function register(Request $request){
       
        //validation
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|confirmed',
        ]);
        //user create
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'user created sucessfully',
            'data'=>[]
        ],200);
    }

    //POST  [email,password]
    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('mytoken')->accessToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Logged In Successfully!',
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Provide correct credentials',
                ], 401); // Unauthorized
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Email',
            ], 404); // Not Found
        }
    }
    

    //Get [Auth:token]
    public function profile(){
        $user = auth()->user();
        return response()->json([
            'status' => true,
            'message' => 'User Info!',
            'user' => $user
        ], 200);
    }

    //Get [Auth:token]
    public function logout(){
        $token = auth()->user()->token();
        $token->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User Logged out',
            //  'token'=>$token
        ], 200);
    }
}
