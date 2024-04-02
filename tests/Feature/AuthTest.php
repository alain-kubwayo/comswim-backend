<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use App\Models\User;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    public function test_user_can_register_with_valid_data() 
    {
        $faker = Faker::create();
        $userData =  [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'date_of_birth' => $faker->date(),
            'gender' => $faker->randomElement(['M', 'F']),
            'telephone' => $faker->phoneNumber,
            'residential_address' => $faker->streetAddress,
            'postal_address' => $faker->postcode,
        ];
        $response = $this->postJson('/api/register', $userData);
        $response->assertStatus(Response::HTTP_CREATED)->assertJsonStructure(['user', 'token']);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $invalidUserData = [
            'first_name' => 1245540000,
            'telephone' => '+25078888999999999990000'
        ];

        $response = $this->postJson('/api/register', $invalidUserData);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'first_name', 
                'last_name', 
                'email', 
                'password', 
                'date_of_birth', 
                'gender', 
                'telephone'
            ]);
    }

    public function test_user_can_login_with_valid_credentials() 
    {
        $faker = Faker::create();
        $user = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'date_of_birth' => $faker->date(),
            'gender' => $faker->randomElement(['M', 'F']),
            'telephone' => $faker->phoneNumber
        ]);
        $loginData = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $loginData);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['user', 'token']);
    }

    public function test_user_cannot_login_with_invalid_credentials() 
    {
        $faker = Faker::create();
        $invalidCredentials = [
            'email' => $faker->unique()->safeEmail,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/login', $invalidCredentials);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertJson(['message' => 'Invalid credentials']);
    }

    public function test_user_can_logout()
    {
        $faker = Faker::create();
        $user = User::create([
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'date_of_birth' => $faker->date(),
            'gender' => $faker->randomElement(['M', 'F']),
            'telephone' => $faker->phoneNumber
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Successfully logged out']);
    }
}
