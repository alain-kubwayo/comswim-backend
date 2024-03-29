<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['date_of_birth', 'gender', 'telephone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
