<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserProfile\UpdateUserProfileRequest;
use App\Models\UserProfile; 
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    public function index() {
        $profile = auth()->user()->user_profile;

        return response()->json($profile);
    }

    public function update($id, UpdateUserProfileRequest $request) {
        try { 
            $validated = $request->validated();

            DB::beginTransaction();

            $profile = UserProfile::where('id', $id)
            ->where('user_id', auth()->user()->id)->first();

            if(is_null($profile)) { 
                return response()->json(['message' => 'Profile not found'], 404);
            }

            $profile->update($validated);

            $fn = $request->first_name;
            $ln = $request->last_name;
            $mn = $request->middle_name;
            $cookedMiddleName = isset($mn) ? ' '. $mn. ' ' : ' ';
            $name = $fn . $cookedMiddleName . $ln;

            auth()->user()->update([
                'name' => $name,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Profile updated successfully',
            ]);
        } catch (\Exception $e) { 
            DB::rollback();

            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
