<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Http\Response;

class ProfileTest extends TestCase
{
    public function test_user_can_get_profile_information() 
    {
        $faker = Faker::create();
        $user =  User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'self' => $faker->randomElement([0, 1])
        ]);

        $userProfileData = [
            'date_of_birth' => $faker->date(),
            'gender' => $faker->randomElement(['M', 'F']),
            'telephone' => $faker->phoneNumber
        ];

        $user->userProfile()->create($userProfileData);

        $userAddressData = [
            'residential_address' => $faker->streetAddress,
            'postal_address' => $faker->postcode
        ];

        $user->address()->create($userAddressData);

        $userHealthInfoData = [
            'chest_disorders' => $faker->randomElement([0, 1]),
            'physical_injuries' => $faker->randomElement([0, 1]), 
            'ear_disorders' => $faker->randomElement([0, 1]), 
            'allergies' => $faker->randomElement([0, 1]),
            'heart_disorders' => $faker->randomElement([0, 1]), 
            'lung_disorders' => $faker->randomElement([0, 1]), 
            'low_muscle_tones' => $faker->randomElement([0, 1]), 
            'wears_spectacles' => $faker->randomElement([0, 1]),
            'takes_medication' => $faker->randomElement([0, 1]), 
            'past_swimming_lessons' => $faker->randomElement([0, 1]), 
            'past_swimming_instructor_duration' => $faker->randomDigitNotNull(),
            'bad_experiences' => $faker->randomElement([0, 1]), 
            'medical_aid_membership' => $faker->randomElement([0, 1]), 
            'medical_aid_name' => $faker->firstName,
            'medical_aid_number' => $faker->randomNumber(5, true), 
            'main_member_full_name' => $faker->name
        ];

        $user->healthInfo()->create($userHealthInfoData);

        if($user->self === 0) {
            $guardianData = [
                'guardian_first_name' => $faker->firstName,
                'guardian_last_name' => $faker->lastName
            ];
            $user->guardian()->create($guardianData);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user-profile');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'self' => $user->self,
                    'created_at' => $user->created_at->toISOString(),
                    'user_profile' => [
                        'date_of_birth' => $user->userProfile->date_of_birth,
                        'gender' => $user->userProfile->gender,
                        'telephone' => $user->userProfile->telephone,
                    ],
                    'address' => [
                        'residential_address' => $user->address->residential_address,
                        'postal_address' => $user->address->postal_address
                    ],
                    'health_info' => [
                        'chest_disorders' => $user->healthInfo->chest_disorders, 
                        'physical_injuries' => $user->healthInfo->physical_injuries, 
                        'ear_disorders' => $user->healthInfo->ear_disorders, 
                        'allergies' => $user->healthInfo->allergies,
                        'heart_disorders' => $user->healthInfo->heart_disorders, 
                        'lung_disorders' => $user->healthInfo->lung_disorders, 
                        'low_muscle_tones' => $user->healthInfo->low_muscle_tones, 
                        'wears_spectacles' => $user->healthInfo->wears_spectacles,
                        'takes_medication' => $user->healthInfo->takes_medication, 
                        'past_swimming_lessons' => $user->healthInfo->past_swimming_lessons, 
                        'past_swimming_instructor_duration' => $user->healthInfo->past_swimming_instructor_duration,
                        'bad_experiences' => $user->healthInfo->bad_experiences, 
                        'medical_aid_membership' => $user->healthInfo->medical_aid_membership, 
                        'medical_aid_name' => $user->healthInfo->medical_aid_name,
                        'medical_aid_number' => $user->healthInfo->medical_aid_number, 
                        'main_member_full_name' => $user->healthInfo->main_member_full_name
                    ],
                    'guardian' => $user->self === 1 ? null :
                    [
                        'guardian_first_name' => $user->guardian->guardian_first_name,
                        'guardian_last_name' => $user->guardian->guardian_last_name,
                    ]
                ]
            ]);
    }

    public function test_user_can_edit_profile()
    {
        $faker = Faker::create();
        $user =  User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'self' => $faker->randomElement([0, 1])
        ]);

        $userProfileData = [
            'date_of_birth' => $faker->date(),
            'gender' => $faker->randomElement(['M', 'F']),
            'telephone' => $faker->phoneNumber
        ];

        $user->userProfile()->create($userProfileData);

        $userAddressData = [
            'residential_address' => $faker->streetAddress,
            'postal_address' => $faker->postcode
        ];

        $user->address()->create($userAddressData);

        $userHealthInfo = [
            'chest_disorders' => $faker->randomElement([0, 1]),
            'physical_injuries' => $faker->randomElement([0, 1]), 
            'ear_disorders' => $faker->randomElement([0, 1]), 
            'allergies' => $faker->randomElement([0, 1]),
            'heart_disorders' => $faker->randomElement([0, 1]), 
            'lung_disorders' => $faker->randomElement([0, 1]), 
            'low_muscle_tones' => $faker->randomElement([0, 1]), 
            'wears_spectacles' => $faker->randomElement([0, 1]),
            'takes_medication' => $faker->randomElement([0, 1]), 
            'past_swimming_lessons' => $faker->randomElement([0, 1]), 
            'past_swimming_instructor_duration' => $faker->randomDigitNotNull(),
            'bad_experiences' => $faker->randomElement([0, 1]), 
            'medical_aid_membership' => $faker->randomElement([0, 1]), 
            'medical_aid_name' => $faker->firstName,
            'medical_aid_number' => $faker->randomNumber(5, true), 
            'main_member_full_name' => $faker->name
        ];

        $user->healthInfo()->create($userHealthInfo);

        if($user->self === 0) {
            $guardianData = [
                'guardian_first_name' => $faker->firstName,
                'guardian_last_name' => $faker->lastName
            ];
            $user->guardian()->create($guardianData);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/edit-profile', [
            'user_type' => 'learner',
            'first_name' => 'New First Name',
            'last_name' => 'New Last Name',
            'telephone' => $faker->phoneNumber,
            'residential_address' => $faker->streetAddress,
            'postal_address' => $faker->postcode,
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'message' => 'User Information Updated Successfully!'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'New First Name'
        ]);
    }
}
