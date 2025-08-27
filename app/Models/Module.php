<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = ['course_id', 'title', 'description'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(ModuleContent::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}
