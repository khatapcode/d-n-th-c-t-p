<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    protected $fillable = ['category_name'];

    public function courses()
    {
        return $this->hasMany(Courses::class, 'category_id', 'category_id');
    }
}
