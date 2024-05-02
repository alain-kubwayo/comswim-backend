<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;

class AdminController extends Controller
{
    public function getUsers() {
        $users = User::where('role', 'learner')->get();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getApplication($id) {
        $user = User::where('id', $id)->first();

        if(!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new UserResource($user);
    }

    public function deleteApplication($id) {
        $user = User::where('id', $id)->first();

        if(!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if($user->profile) {
            $user->profile()->delete();
        }

        if($user->address) {
            $user->address()->delete();
        }

        if($user->healthInfo) {
            $user->healthInfo()->delete();
        }

        if($user->guardian) {
            $user->guardian()->delete();
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }

    public function updateApplication(Request $request, $id) {
        $user = User::where('id', $id)->first();

        if(!$user) {
            return response()->json(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email
        ]);

        $user->profile()->update([
            'telephone' => $request->telephone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender
        ]);

        $user->address()->update([
            'residential_address' => $request->residential_address,
            'postal_address' => $request->postal_address
        ]);

        if($request->guardian_first_name && $request->guardian_last_name) {
            $user->guardian()->update([
                'guardian_first_name' => $request->guardian_first_name,
                'guardian_last_name' => $request->guardian_last_name
            ]);
        }

        return response()->json(['message' => 'Application Updated Successfully!'], Response::HTTP_OK);
    }
}
