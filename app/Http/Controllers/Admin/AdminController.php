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

        if($user->userProfile) {
            $user->userProfile()->delete();
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
}
