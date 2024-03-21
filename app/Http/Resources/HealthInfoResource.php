<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'chest_disorders' => $this->chest_disorders, 
            'physical_injuries' => $this->physical_injuries, 
            'ear_disorders' => $this->ear_disorders, 
            'allergies' => $this->allergies,
            'heart_disorders' => $this->heart_disorders, 
            'lung_disorders' => $this->lung_disorders, 
            'low_muscle_tones' => $this->low_muscle_tones, 
            'wears_spectacles' => $this->wears_spectacles,
            'takes_medication' => $this->takes_medication, 
            'past_swimming_lessons' => $this->past_swimming_lessons, 
            'past_swimming_instructor_duration' => $this->past_swimming_instructor_duration,
            'bad_experiences' => $this->bad_experiences, 
            'medical_aid_membership' => $this->medical_aid_membership, 
            'medical_aid_name' => $this->medical_aid_name,
            'medical_aid_number' => $this->medical_aid_number, 
            'main_member_full_name' => $this->main_member_full_name
        ];
    }
}
