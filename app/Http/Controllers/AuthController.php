<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function home(){
        return 'home';
    }

    public function login(LoginUserRequest $request){
        if(!Auth::attempt($request->only('email','password'))){
            return $this->error('','Credentials doesnot match',401);
        }else{
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token= $user->createToken('api of '.$user->name);
            // return $token->plainTextToken;
            // $user = User::where('email',$request->email)->first();
            $response = new UserResource($user);
            return $this->success([
                'user'=>$response,
                'token'=>$token->plainTextToken
            ]);
        }
    }

    public function register(StoreUserRequest $request){

        $user = User::create([
            'name'=>$request->validated('name'),
            'email'=>$request->validated('email'),
            'password'=>Hash::make($request->validated('password')),
        ]);
        // dd($user);
        return $this->success([
            'user'=>$user,
            'token'=>$user->createToken('api token of'.$user->name)->plainTextToken
        ]);
        // return response()->json('register page');
    } 

    public function logout(){
        // /** @var \App\Models\User $user **/
        $user=Auth::user()->currentAccessToken()->delete();
        // $token=$user->currentAccessToken();
        return $this->success([
            'message'=>'You have successfully logged out and token has been deleted'
        ],'',200
    );

    }
}


