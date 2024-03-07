<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserHealthInfo extends Model
{
    use HasFactory;

    protected $fillable = ['chest_disorders', 'physical_injuries', 'ear_disorders', 'allergies', 'heart_disorders',
    'lung_disorders', 'low_muscle_tones', 'wears_spectacles', 'takes_medication', 'past_swimming_lessons', 'past_swimming_instructor_duration', 'bad_experiences', 'medical_aid_membership', 'medical_aid_name', 'medical_aid_number',
    'main_member_full_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
