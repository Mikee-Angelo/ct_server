<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Logs;

class LogsController extends Controller
{
    //
    public function users(Request $request){
        /**
         * Check if user belongs to level 1 users
         */
        $user = Logs::select('logs.id', 'users_id', 'places_id', 'logs.created_at', 'logs.updated_at', 'phone', 'address', 'email_verified_at', 'users_profiles_id', 'first_name', 'last_name', 'full_name')
        ->join('users', 'users.id', '=', 'logs.places_id')
        ->join('users_profiles', 'users_profiles.id', '=', 'users.users_profiles_id')
        ->where([
            ['users_id', '=', $this->guard()->user()->id],
        ])
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json(['status' =>  true, 'data' => $user]);
    }
    //
    public function latest(Request $request){
        /**
         * Check if user belongs to level 1 users
         */
        $user = Logs::select('logs.id', 'users_id', 'places_id', 'logs.created_at', 'logs.updated_at', 'phone', 'address', 'email_verified_at', 'users_profiles_id', 'first_name', 'last_name', 'full_name')
        ->join('users', 'users.id', '=', 'logs.places_id')
        ->join('users_profiles', 'users_profiles.id', '=', 'users.users_profiles_id')
        ->where([
            ['users_id', '=', $this->guard()->user()->id],
        ])
        ->orderBy('id', 'DESC')
        ->first();

        return response()->json(['status' =>  true, 'data' => $user]);
    }

    public function usersCreate(Request $request){
        $v = $request->validate([
            'places_id' => 'required|uuid',
        ]);

        if(!$v){
            return response()->json($validator->errors()->toJson(), 400);
        }

        /**
         * Check if user belongs to level 1 users
         */
        $user = User::where([
            ['id', '=', $this->guard()->user()->id],
            ['users_profiles_id', '!=', null],
        ]);

        $places = User::where([
            ['uuid', '=', $v['places_id']],
        ]);

        if(!$user->exists()){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if(!$places->exists()){
            return response()->json(['error' => 'Account not found'], 400);
        }

        if($this->guard()->user()->id == $places->first()->id){
            return response()->json(['error' => 'Invalid QR Data'], 400);
        }

        Logs::create([
            'users_id' => $this->guard()->user()->id,
            'places_id' => $places->first()->id,
        ]);

        return response()->json(['message' => 'Logs Successfully Added']);
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
