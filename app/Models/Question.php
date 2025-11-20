<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'question',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'time_limit',
        'question_type',
        'sort_order',
        'is_active',
        'metadata',
        'image_url',
        'video_url',
        'audio_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'metadata' => 'array',
        'points' => 'integer',
        'time_limit' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default attribute values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'points' => 1,
        'question_type' => 'multiple_choice',
        'sort_order' => 0,
        'is_active' => true,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'difficulty_level',
        'success_rate',
        'formatted_question_type',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($question) {
            // Set default sort order if not provided
            if (empty($question->sort_order)) {
                $maxOrder = static::where('quiz_id', $question->quiz_id)->max('sort_order');
                $question->sort_order = $maxOrder ? $maxOrder + 1 : 1;
            }

            // Validate options based on question type
            $question->validateOptions();
        });

        static::updating(function ($question) {
            // Validate options based on question type
            $question->validateOptions();
        });

        static::created(function ($question) {
            // Create initial statistics record
            $question->statistics()->create();
        });

        static::deleting(function ($question) {
            // Clean up related records
            $question->hints()->delete();
            $question->statistics()->delete();
        });
    }

    /**
     * Relationship with Quiz
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relationship with Question Statistics
     */
    public function statistics(): HasOne
    {
        return $this->hasOne(QuestionStatistic::class);
    }

    /**
     * Relationship with Question Hints
     */
    public function hints(): HasMany
    {
        return $this->hasMany(QuestionHint::class)->orderBy('sort_order');
    }

    /**
     * Relationship with Quiz Results (through answers)
     */
    public function quizResults()
    {
        return $this->hasManyThrough(QuizResult::class, 'answers', 'question_id', 'id');
    }

    /**
     * Scope active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope questions by quiz
     */
    public function scopeByQuiz($query, $quizId)
    {
        return $query->where('quiz_id', $quizId);
    }

    /**
     * Scope questions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    /**
     * Scope ordered questions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Check if the provided answer is correct
     */
    public function isCorrectAnswer(string $answer): bool
    {
        return $this->correct_answer === $answer;
    }

    /**
     * Validate options based on question type
     */
    public function validateOptions(): void
    {
        switch ($this->question_type) {
            case 'multiple_choice':
            case 'single_choice':
                if (empty($this->options) || ! is_array($this->options)) {
                    throw new \InvalidArgumentException('Multiple choice questions must have options array.');
                }
                if (! in_array($this->correct_answer, $this->options)) {
                    throw new \InvalidArgumentException('Correct answer must be one of the provided options.');
                }
                break;

            case 'true_false':
                if (! in_array($this->correct_answer, ['True', 'False'])) {
                    throw new \InvalidArgumentException('True/False questions must have correct answer as "True" or "False".');
                }
                break;

            case 'short_answer':
                // For short answer, we might want to store multiple acceptable answers
                if (is_array($this->correct_answer)) {
                    $this->correct_answer = json_encode($this->correct_answer);
                }
                break;
        }
    }

    /**
     * Get available hints ordered by points deduction (lowest deduction first)
     */
    public function getAvailableHints()
    {
        return $this->hints()
            ->active()
            ->orderBy('points_deduction')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get the next question in the quiz
     */
    public function getNextQuestion(): ?Question
    {
        return static::where('quiz_id', $this->quiz_id)
            ->where('sort_order', '>', $this->sort_order)
            ->active()
            ->ordered()
            ->first();
    }

    /**
     * Get the previous question in the quiz
     */
    public function getPreviousQuestion(): ?Question
    {
        return static::where('quiz_id', $this->quiz_id)
            ->where('sort_order', '<', $this->sort_order)
            ->active()
            ->ordered()
            ->orderByDesc('sort_order')
            ->first();
    }

    /**
     * Update question statistics
     */
    public function updateStatistics(bool $isCorrect, float $timeTaken = 0): void
    {
        $stats = $this->statistics()->firstOrCreate([]);

        $stats->total_attempts++;

        if ($isCorrect) {
            $stats->correct_attempts++;
        } else {
            $stats->incorrect_attempts++;
        }

        // Update average time taken
        if ($timeTaken > 0) {
            $totalTime = ($stats->average_time_taken * ($stats->total_attempts - 1)) + $timeTaken;
            $stats->average_time_taken = $totalTime / $stats->total_attempts;
        }

        // Calculate difficulty level (0 = easy, 1 = hard)
        if ($stats->total_attempts > 0) {
            $stats->difficulty_level = 1 - ($stats->correct_attempts / $stats->total_attempts);
        }

        $stats->save();
    }

    /**
     * Get question types with their configurations
     */
    public static function getQuestionTypes(): array
    {
        return [
            'multiple_choice' => [
                'name' => 'Multiple Choice',
                'has_options' => true,
                'multiple_answers' => true,
            ],
            'single_choice' => [
                'name' => 'Single Choice',
                'has_options' => true,
                'multiple_answers' => false,
            ],
            'true_false' => [
                'name' => 'True/False',
                'has_options' => false,
                'multiple_answers' => false,
            ],
            'short_answer' => [
                'name' => 'Short Answer',
                'has_options' => false,
                'multiple_answers' => false,
            ],
            'fill_in_blank' => [
                'name' => 'Fill in the Blank',
                'has_options' => false,
                'multiple_answers' => false,
            ],
            'matching' => [
                'name' => 'Matching',
                'has_options' => true,
                'multiple_answers' => true,
            ],
        ];
    }

    /**
     * Accessor for difficulty level
     */
    public function getDifficultyLevelAttribute(): ?float
    {
        return $this->statistics?->difficulty_level;
    }

    /**
     * Accessor for success rate
     */
    public function getSuccessRateAttribute(): ?float
    {
        if (! $this->statistics || $this->statistics->total_attempts === 0) {
            return null;
        }

        return ($this->statistics->correct_attempts / $this->statistics->total_attempts) * 100;
    }

    /**
     * Accessor for formatted question type
     */
    public function getFormattedQuestionTypeAttribute(): string
    {
        $types = static::getQuestionTypes();

        return $types[$this->question_type]['name'] ?? ucfirst(str_replace('_', ' ', $this->question_type));
    }

    /**
     * Check if question has media (image, video, audio)
     */
    public function hasMedia(): bool
    {
        return ! empty($this->image_url) || ! empty($this->video_url) || ! empty($this->audio_url);
    }

    /**
     * Get media URLs
     */
    public function getMediaUrls(): array
    {
        return [
            'image' => $this->image_url,
            'video' => $this->video_url,
            'audio' => $this->audio_url,
        ];
    }

    /**
     * Get the maximum points that can be achieved for this question
     */
    public function getMaxPoints(): int
    {
        return $this->points;
    }

    /**
     * Get points after hint deduction
     */
    public function getPointsAfterHint(int $hintDeduction): int
    {
        return max(0, $this->points - $hintDeduction);
    }

    /**
     * Check if question is multiple choice
     */
    public function isMultipleChoice(): bool
    {
        return in_array($this->question_type, ['multiple_choice', 'single_choice']);
    }

    /**
     * Check if question requires manual grading
     */
    public function requiresManualGrading(): bool
    {
        return in_array($this->question_type, ['short_answer', 'fill_in_blank']);
    }

    /**
     * Get random options (for shuffling answers)
     */
    public function getShuffledOptions(): ?array
    {
        if (! $this->options || ! is_array($this->options)) {
            return null;
        }

        $options = $this->options;
        shuffle($options);

        return $options;
    }
}
