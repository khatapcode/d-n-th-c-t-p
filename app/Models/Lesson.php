<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    // Khóa chính
    protected $primaryKey = 'lesson_id';

    protected $fillable = [
        'lesson_name',
        'lesson_video',
        'lesson_duration',
        'course_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function question()
    {
        return $this->hasMany(Question::class, 'question_id', 'question_id');
    }
}
