<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class ProfileController extends Controller
{
    public function getUserInformation() {
        $user = Auth::user();

        if($user) {
            return new UserResource($user);
        } else {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

    } 
}
