<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'avatar',
    ];

    public function parentUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'child_profile_id');
    }
}
