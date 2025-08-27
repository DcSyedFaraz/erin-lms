<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleContent extends Model
{
    protected $fillable = ['module_id', 'type', 'path', 'text'];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
