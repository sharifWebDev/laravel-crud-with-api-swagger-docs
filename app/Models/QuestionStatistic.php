<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionStatistic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'question_statistics';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'total_attempts',
        'correct_attempts',
        'incorrect_attempts',
        'average_time_taken',
        'difficulty_level',
        'answer_distribution',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_attempts' => 'integer',
        'correct_attempts' => 'integer',
        'incorrect_attempts' => 'integer',
        'average_time_taken' => 'float',
        'difficulty_level' => 'float',
        'answer_distribution' => 'array',
    ];

    /**
     * Relationship with Question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Update answer distribution
     */
    public function updateAnswerDistribution(string $answer, bool $isCorrect): void
    {
        $distribution = $this->answer_distribution ?? [];

        if (! isset($distribution[$answer])) {
            $distribution[$answer] = [
                'count' => 0,
                'is_correct' => $isCorrect,
            ];
        }

        $distribution[$answer]['count']++;

        $this->answer_distribution = $distribution;
        $this->save();
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute(): float
    {
        if ($this->total_attempts === 0) {
            return 0.0;
        }

        return ($this->correct_attempts / $this->total_attempts) * 100;
    }

    /**
     * Get most common incorrect answer
     */
    public function getMostCommonIncorrectAnswer(): ?string
    {
        if (! $this->answer_distribution) {
            return null;
        }

        $incorrectAnswers = array_filter($this->answer_distribution, function ($data) {
            return ! $data['is_correct'];
        });

        if (empty($incorrectAnswers)) {
            return null;
        }

        $maxCount = max(array_column($incorrectAnswers, 'count'));
        $mostCommon = array_filter($incorrectAnswers, function ($data) use ($maxCount) {
            return $data['count'] === $maxCount;
        });

        return array_key_first($mostCommon);
    }
}
