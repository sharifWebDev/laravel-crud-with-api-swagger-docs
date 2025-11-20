<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->json('options')->nullable()->comment('For multiple choice questions - array of choices');
            $table->string('correct_answer');
            $table->text('explanation')->nullable()->comment('Explanation for the correct answer');
            $table->integer('points')->default(1)->comment('Points awarded for correct answer');
            $table->integer('time_limit')->nullable()->comment('Time limit for this question in seconds');
            $table->string('question_type')->default('multiple_choice')->comment('multiple_choice, true_false, short_answer, etc.');
            $table->integer('sort_order')->default(0)->comment('Order of questions in quiz');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable()->comment('Additional question metadata');
            $table->string('image_url')->nullable()->comment('URL for question image');
            $table->string('video_url')->nullable()->comment('URL for question video');
            $table->string('audio_url')->nullable()->comment('URL for question audio');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['quiz_id', 'is_active']);
            $table->index(['quiz_id', 'sort_order']);
            $table->index(['is_active']);
        });

        // Create question statistics table for analytics
        Schema::create('question_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('total_attempts')->default(0);
            $table->integer('correct_attempts')->default(0);
            $table->integer('incorrect_attempts')->default(0);
            $table->float('average_time_taken')->default(0)->comment('Average time taken to answer in seconds');
            $table->float('difficulty_level')->default(0)->comment('Calculated difficulty level 0-1');
            $table->json('answer_distribution')->nullable()->comment('Distribution of answers chosen');
            $table->timestamps();

            $table->unique(['question_id']);
        });

        // Create question hints table
        Schema::create('question_hints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('hint_text');
            $table->integer('points_deduction')->default(0)->comment('Points deducted when using this hint');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['question_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_hints');
        Schema::dropIfExists('question_statistics');
        Schema::dropIfExists('questions');
    }
};
