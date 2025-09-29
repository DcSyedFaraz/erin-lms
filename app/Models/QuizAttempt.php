<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'child_profile_id',
        'course_id',
        'module_id',
        'started_at',
        'completed_at',
        'status',
        'total_points',
        'max_points',
        'question_order',
        'current_index',
        'time_limit_minutes',
    ];

    protected $casts = [
        'question_order' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(ChildProfile::class, 'child_profile_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function endsAt(): ?CarbonInterface
    {
        if (!$this->started_at) return null;
        return $this->started_at->copy()->addMinutes($this->time_limit_minutes);
    }
}

