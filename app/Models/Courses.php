<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    // Khóa chính
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_name',
        'course_img',
        'course_description',
        'course_status',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lessons::class, 'lesson_id', 'lesson_id');
    }

    public function registercourses()
    {
        return $this->hasMany(RegisterCourse::class, 'rc_id', 'rc_id');
    }
}
