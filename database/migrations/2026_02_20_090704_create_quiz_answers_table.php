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
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('selected_option_id')->nullable()->constrained('options')->onDelete('set null');
            $table->text('answer_text')->nullable();            // Untuk short_answer & essay
            $table->boolean('is_correct')->nullable();            // null = belum dinilai (essay)
            $table->integer('score')->nullable();                 // null = belum dinilai (essay)
            $table->text('feedback')->nullable();                 // Feedback dari instructor
            $table->foreignId('graded_by')->nullable()->constrained('users')->onDelete('set null'); // Instructor
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};
