<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
