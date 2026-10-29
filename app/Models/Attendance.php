<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'art_class_id',
        'student_id',
        'session_date',
        'status',
        'note',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function artClass()
    {
        return $this->belongsTo(ArtClass::class, 'art_class_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
