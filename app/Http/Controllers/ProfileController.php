<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function getUserInformation() {
        $user = Auth::user();
        if(!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return new UserResource($user);
    }

    public function editProfile(EditProfileRequest $request) {
        if($request->user_type === 'guardian') {
            $request->validate([
                'guardian_first_name' => 'required|string|max:255',
                'guardian_last_name' => 'required|string|max:255'
            ]);
        }
        $user = User::findOrFail(Auth::user()->id);

        if(!$user) {
            return response()->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
        ]);

        $user->profile()->update([ 'telephone' => $request->telephone ]);

        $user->address()->update([
            'residential_address' => $request->residential_address,
            'postal_address' => $request->postal_address
        ]);

        if($request->user_type === 'guardian') {
            $user->guardian()->update([
                'guardian_first_name' => $request->guardian_first_name,
                'guardian_last_name' => $request->guardian_last_name
            ]);
        }

        return response()->json(['message' => 'User Information Updated Successfully!'], Response::HTTP_OK);
    }
}
