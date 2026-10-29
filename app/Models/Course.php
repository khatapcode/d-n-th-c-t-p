<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'art_courses';

    protected $fillable = [
        'name',
        'duration_hours',
        'level',
        'tuition_fee',
        'description',
        'image',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'tuition_fee' => 'integer',
        'duration_hours' => 'integer',
    ];

    public function classes()
    {
        return $this->hasMany(ArtClass::class, 'course_id');
    }

    public function activeClasses()
    {
        return $this->classes()->where('status', 'ongoing');
    }
}
