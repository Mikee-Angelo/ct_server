<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\UsersProfile;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', [
    //         'prefix' => ['auth'],
    //     ]);
    // }

    public function register(Request $request){
        $validator = $request->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'address' => ['required'],
            'phone' => ['required', 'unique:users', 'max:11', 'min:11'],
            'password' => ['required', 'min:8'],
        ]);

        if(!$validator){
            return response()->json($validator->errors()->toJson(), 400);
        }  

        $uuid = Str::uuid()->toString();
        $file = $uuid.'.svg';
        $path = '../public/qr/'.$file;

        \QrCode::size(500)->format('svg')->size(300)->generate($uuid, $path);

        $user = UsersProfile::create([
            'first_name' => ucwords($validator['first_name']),
            'last_name' => ucwords($validator['last_name']),
            'full_name' => ucwords($validator['first_name']).' '.ucwords($validator['last_name']),
        ]);

        User::create([
            'uuid' => $uuid ,
            'phone' => $validator['phone'],
            'password' => Hash::make($validator['password']),
            'users_profiles_id' => $user->id,
            'address' => ucwords($validator['address']),
            'qr' => $file,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered'
        ], 201);
    }
    
    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function login(Request $request){
        $validate = $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required'
        ]);

        $credentials = $request->only('phone', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            if(is_null($this->guard()->user()->users_profiles_id)){
                return response()->json(['error' => 'Incorrect phone number or password'], 401);
            }

            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Incorrect phone number or password'], 401);

    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {

        return response()->json($this->guard()->user()->join('users_profiles', 'users_profiles.id', '=', 'users.users_profiles_id')->where('users.id', $this->guard()->user()->id)->first());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}