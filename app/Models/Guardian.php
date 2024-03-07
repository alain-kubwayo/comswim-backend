<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'guardian_first_name', 'guardian_last_name'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
