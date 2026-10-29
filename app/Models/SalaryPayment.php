<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'period_month',
        'period_year',
        'teaching_hours',
        'amount',
        'status',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'teaching_hours' => 'float',
        'amount' => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
