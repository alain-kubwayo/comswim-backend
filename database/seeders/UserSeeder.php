<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@comswim.com',
            'password' => 'password',
            'date_of_birth' => '1990-05-15',
            'gender' => 'M',
            'telephone' => '1234567890',
            'residential_address' => 'kigali, rwanda',
            'postal_address' => '00000',
            'role' => 'admin',
            'chest_disorders' => true,
            'physical_injuries' => true,
            'ear_disorders' => true,
            'allergies' => true,
            'heart_disorders' => true,
            'lung_disorders' => true,
            'low_muscle_tones' => true,
            'wears_spectacles' => true,
            'takes_medication' => true,
            'past_swimming_lessons' => true,
            'past_swimming_instructor_duration' => null,
            'bad_experiences' => false,
            'medical_aid_membership' => false,
            'medical_aid_name' => null,
            'medical_aid_number' => null,
            'main_member_full_name' => null
        ]);

        $this->createUser([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'password' => 'password',
            'date_of_birth' => '1995-05-15',
            'gender' => 'F',
            'telephone' => '123499003',
            'residential_address' => 'accra, ghana',
            'postal_address' => '12000',
            'role' => 'learner',
            'chest_disorders' => true,
            'physical_injuries' => true,
            'ear_disorders' => false,
            'allergies' => true,
            'heart_disorders' => true,
            'lung_disorders' => true,
            'low_muscle_tones' => true,
            'wears_spectacles' => true,
            'takes_medication' => true,
            'past_swimming_lessons' => true,
            'past_swimming_instructor_duration' => 'Kenny, 1 month',
            'bad_experiences' => false,
            'medical_aid_membership' => false,
            'medical_aid_name' => null,
            'medical_aid_number' => null,
            'main_member_full_name' => null
        ]);

        $this->createUser([
            'first_name' => 'Jonas',
            'last_name' => 'Doe',
            'email' => 'jonas.doe@example.com',
            'password' => 'password',
            'date_of_birth' => '2001-05-15',
            'gender' => 'M',
            'telephone' => '1234567890',
            'residential_address' => 'johannesburg, south africa',
            'postal_address' => '23022',
            'role' => 'learner',
            'chest_disorders' => true,
            'physical_injuries' => true,
            'ear_disorders' => true,
            'allergies' => true,
            'heart_disorders' => true,
            'lung_disorders' => true,
            'low_muscle_tones' => true,
            'wears_spectacles' => true,
            'takes_medication' => true,
            'past_swimming_lessons' => true,
            'past_swimming_instructor_duration' => 'Johnny, 3 months',
            'bad_experiences' => false,
            'medical_aid_membership' => false,
            'medical_aid_name' => null,
            'medical_aid_number' => null,
            'main_member_full_name' => null
        ]);
    }

    private function createUser(array $userData)
    {
        $user = User::create([
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'remember_token' => Str::random(40),
            'role' => $userData['role']
        ]);

        $user->profile()->create([
            'date_of_birth' => $userData['date_of_birth'],
            'gender' => $userData['gender'],
            'telephone' => $userData['telephone'],
        ]);

        $user->address()->create([
            'residential_address' => $userData['residential_address'],
            'postal_address' => $userData['postal_address'],
        ]);

        $user->healthInfo()->create([
            'chest_disorders' => $userData['chest_disorders'],
            'physical_injuries' => $userData['physical_injuries'],
            'ear_disorders' => $userData['ear_disorders'],
            'allergies' => $userData['allergies'],
            'heart_disorders' => $userData['heart_disorders'],
            'lung_disorders' => $userData['lung_disorders'],
            'low_muscle_tones' => $userData['low_muscle_tones'],
            'wears_spectacles' => $userData['wears_spectacles'],
            'takes_medication' => $userData['takes_medication'],
            'past_swimming_lessons' => $userData['past_swimming_lessons'],
            'past_swimming_instructor_duration' => $userData['past_swimming_instructor_duration'],
            'bad_experiences' => $userData['bad_experiences'],
            'medical_aid_membership' => $userData['medical_aid_membership'],
            'medical_aid_name' => $userData['medical_aid_name'],
            'medical_aid_number' => $userData['medical_aid_number'],
            'main_member_full_name' => $userData['main_member_full_name']
        ]);

        return $user;
    }
}
