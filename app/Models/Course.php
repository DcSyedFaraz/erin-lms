<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'category_id',
        'level_id',
        'is_premium',
        'price',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }


    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }
}
