<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    // Khóa chính
    protected $primaryKey = 'question_id';

    protected $fillable = [
        'question_name',
        'question_a',
        'question_b',
        'question_c',
        'question_d',
        'question_correct',
        'lesson_id',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'lesson_id');
    }
}
