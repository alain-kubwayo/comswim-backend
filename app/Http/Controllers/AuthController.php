<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\RegisterMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|in:M,F',
            'telephone' => 'required|string|max:20'
        ]);



        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(40)
        ]);

        if($user) {
            Mail::to($request->email)->send(new RegisterMail($user));
            $userProfileData = $request->only(['date_of_birth', 'gender', 'telephone']);
            $user->userProfile()->create($userProfileData);

            $addressData = $request->only(['residential_address', 'postal_address']);
            $user->address()->create($addressData);

            $healthInfoDataFields = [
                'chest_disorders', 'physical_injuries', 'ear_disorders', 'allergies',
                'heart_disorders', 'lung_disorders', 'low_muscle_tones', 'wears_spectacles',
                'takes_medication', 'past_swimming_lessons', 'past_swimming_instructor_duration',
                'bad_experiences', 'medical_aid_membership', 'medical_aid_name',
                'medical_aid_number', 'main_member_full_name'
            ];

            $healthInfoData = $request->only([
                'chest_disorders', 'physical_injuries', 'ear_disorders', 'allergies',
                'heart_disorders', 'lung_disorders', 'low_muscle_tones', 'wears_spectacles',
                'takes_medication', 'past_swimming_lessons', 'past_swimming_instructor_duration',
                'bad_experiences', 'medical_aid_membership', 'medical_aid_name',
                'medical_aid_number', 'main_member_full_name'
            ]);

            foreach ($healthInfoDataFields as $field) {
                $healthInfoData[$field] = $healthInfoData[$field] ?? false;
            }

            $user->healthInfo()->create($healthInfoData);

            if($request->user_type === 'guardian') {
                $request->validate([
                    'guardian_first_name' => 'required|string|max:255',
                    'guardian_last_name' => 'required|string|max:255',
                ]);
                $guardianData = $request->only(['guardian_first_name', 'guardian_last_name']);
                $user->guardian()->create($guardianData);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
        } else {
            return response()->json(['message' => 'Could not create the user'], Response::HTTP_BAD_REQUEST);

        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function verifyEmail($token) {
        $user = User::where('remember_token', '=', $token)->first();
        if(!empty($user)) {
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->remember_token = Str::random(40);
                $user->save();

                return response()->json(['success' => true, 'message' => 'Email verified successfully'], RESPONSE::HTTP_OK);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], RESPONSE::HTTP_NOT_FOUND);
        }
    }
}
