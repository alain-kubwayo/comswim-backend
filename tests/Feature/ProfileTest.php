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
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $faker = Faker::create();
        $this->user = User::create([
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

        $this->user->profile()->create($userProfileData);

        $userAddressData = [
            'residential_address' => $faker->streetAddress,
            'postal_address' => $faker->postcode
        ];

        $this->user->address()->create($userAddressData);

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

        $this->user->healthInfo()->create($userHealthInfoData);

        if($this->user->self === 0) {
            $guardianData = [
                'guardian_first_name' => $faker->firstName,
                'guardian_last_name' => $faker->lastName
            ];
            $this->user->guardian()->create($guardianData);
        }
    }

    public function test_user_can_get_profile_information()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/user-profile');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $this->user->id,
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'email' => $this->user->email,
                    'self' => $this->user->self,
                    'created_at' => $this->user->created_at->toISOString(),
                    'user_profile' => [
                        'date_of_birth' => $this->user->profile->date_of_birth,
                        'gender' => $this->user->profile->gender,
                        'telephone' => $this->user->profile->telephone,
                    ],
                    'address' => [
                        'residential_address' => $this->user->address->residential_address,
                        'postal_address' => $this->user->address->postal_address
                    ],
                    'health_info' => [
                        'chest_disorders' => $this->user->healthInfo->chest_disorders,
                        'physical_injuries' => $this->user->healthInfo->physical_injuries,
                        'ear_disorders' => $this->user->healthInfo->ear_disorders,
                        'allergies' => $this->user->healthInfo->allergies,
                        'heart_disorders' => $this->user->healthInfo->heart_disorders,
                        'lung_disorders' => $this->user->healthInfo->lung_disorders,
                        'low_muscle_tones' => $this->user->healthInfo->low_muscle_tones,
                        'wears_spectacles' => $this->user->healthInfo->wears_spectacles,
                        'takes_medication' => $this->user->healthInfo->takes_medication,
                        'past_swimming_lessons' => $this->user->healthInfo->past_swimming_lessons,
                        'past_swimming_instructor_duration' => $this->user->healthInfo->past_swimming_instructor_duration,
                        'bad_experiences' => $this->user->healthInfo->bad_experiences,
                        'medical_aid_membership' => $this->user->healthInfo->medical_aid_membership,
                        'medical_aid_name' => $this->user->healthInfo->medical_aid_name,
                        'medical_aid_number' => $this->user->healthInfo->medical_aid_number,
                        'main_member_full_name' => $this->user->healthInfo->main_member_full_name
                    ],
                    'guardian' => $this->user->self === 1 ? null :
                    [
                        'guardian_first_name' => $this->user->guardian->guardian_first_name,
                        'guardian_last_name' => $this->user->guardian->guardian_last_name,
                    ]
                ]
            ]);
    }

    public function test_user_can_edit_profile()
    {
        $token = $this->user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/edit-profile', [
            'user_type' => 'learner',
            'first_name' => 'New First Name',
            'last_name' => 'New Last Name',
            'telephone' => '+250788888888',
            'residential_address' => 'KG 690 Ave',
            'postal_address' => '00000',
        ]);

        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'message' => 'User Information Updated Successfully!'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'first_name' => 'New First Name'
        ]);
    }
}
