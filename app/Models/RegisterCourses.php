<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterCourses extends Model
{
    use HasFactory;

    // Khóa chính
    protected $primaryKey = 'rc_id';

    protected $fillable = [
        'rc_status',
        'course_id',
        'user_id',
    ];
      
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
