<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtClass extends Model
{
    use HasFactory;

    protected $table = 'art_classes';

    protected $fillable = [
        'code',
        'name',
        'course_id',
        'teacher_id',
        'schedule',
        'room',
        'status',
        'start_date',
        'end_date',
        'max_students',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** Tất cả bản ghi đăng ký, gồm cả chờ duyệt / bị từ chối. */
    public function members()
    {
        return $this->belongsToMany(User::class, 'class_student', 'art_class_id', 'student_id')
            ->withPivot('status', 'note', 'enrolled_at', 'decided_at');
    }

    /** Học viên đã được duyệt vào lớp. */
    public function students()
    {
        return $this->members()->wherePivot('status', 'approved');
    }

    /** Yêu cầu đăng ký đang chờ duyệt. */
    public function pendingStudents()
    {
        return $this->members()->wherePivot('status', 'pending');
    }

    public function seatsLeft(): int
    {
        return max(0, $this->max_students - $this->students()->count());
    }

    public function isFull(): bool
    {
        return $this->seatsLeft() === 0;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'ongoing' => 'Đang học',
            'completed' => 'Đã kết thúc',
            default => 'Sắp mở',
        };
    }

    public function enrollmentStatusFor(?int $userId): ?string
    {
        if (!$userId) {
            return null;
        }

        return $this->members()
            ->wherePivot('student_id', $userId)
            ->first()?->pivot->status;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'art_class_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'art_class_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['upcoming', 'ongoing']);
    }
}
