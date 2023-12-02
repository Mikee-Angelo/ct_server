<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'first_name' => 'required|string|max:100',
                'middle_name' => 'string|nullable|max:50',
                'last_name' => 'required|string|max:100',
                'address' => 'required|string|max:100',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'device_id' => ['required', 'string']
            ]);

            $fn = $request->first_name;
            $ln = $request->last_name;
            $mn = $request->middle_name;
            $cookedMiddleName = isset($mn) ? ' '. $mn. ' ' : ' ';
            $name = $fn . $cookedMiddleName . $ln;

            $user = User::create([
                'name' => $name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $profile = UserProfile::create([
                'user_id' => $user->id,
                'first_name' => $fn,
                'middle_name' => $mn,
                'last_name' => $ln,
                'address' => $request->address,
            ]);

            /**
             * If the user register through api it sends JSON response
             */
            $user->assignRole('User');

            DB::commit();

            return response()->json([
                'message' => "Registered successfully",
                'token' => $user->createToken($request->device_id)->plainTextToken,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
