<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'type',
        'time_limit',
        'points',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Relationship with active Questions
     */
    public function activeQuestions()
    {
        return $this->questions()->active();
    }

    /**
     * Get total points for the quiz
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions()->sum('points');
    }

    /**
     * Get total questions count
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questions()->count();
    }

    /**
     * Get average difficulty level
     */
    public function getAverageDifficultyAttribute(): ?float
    {
        $avgDifficulty = $this->questions()
            ->join('question_statistics', 'questions.id', '=', 'question_statistics.question_id')
            ->avg('question_statistics.difficulty_level');

        return $avgDifficulty ? round($avgDifficulty, 2) : null;
    }

    /**
     * Get questions by type
     */
    public function getQuestionsByType(string $type)
    {
        return $this->questions()->byType($type)->get();
    }
}
