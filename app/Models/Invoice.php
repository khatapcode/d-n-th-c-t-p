<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'student_id',
        'art_class_id',
        'amount',
        'status',
        'method',
        'paid_note',
        'paid_at',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function artClass()
    {
        return $this->belongsTo(ArtClass::class, 'art_class_id');
    }
}
