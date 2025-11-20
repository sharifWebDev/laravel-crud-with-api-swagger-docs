<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->where('is_active', true);
    }

    public function activeQuizzes()
    {
        return $this->quizzes()->where('is_active', true);
    }
}
