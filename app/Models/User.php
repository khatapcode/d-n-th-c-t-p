<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_GUEST = 'GUEST';
    public const ROLE_STUDENT = 'STUDENT';
    public const ROLE_TEACHER = 'TEACHER';
    public const ROLE_ADMIN = 'ADMIN';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'bio',
        'address',
        'hourly_rate',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'hourly_rate' => 'integer',
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function homeRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'admin.dashboard',
            self::ROLE_TEACHER => 'teacher.account',
            default => 'student.account',
        };
    }

    public function teachingClasses()
    {
        return $this->hasMany(ArtClass::class, 'teacher_id');
    }

    public function enrolledClasses()
    {
        return $this->belongsToMany(ArtClass::class, 'class_student', 'student_id', 'art_class_id')
            ->withPivot('enrolled_at');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class, 'teacher_id');
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
