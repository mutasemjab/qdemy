<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->json('selected_options')->nullable(); // For multiple choice (array of option IDs)
            $table->text('essay_answer')->nullable(); // For essay questions
            $table->boolean('is_correct')->nullable(); // Auto-calculated for MC/TF, manual for essay
            $table->decimal('score', 5, 2)->default(0.00); // Score achieved for this question
            $table->datetime('answered_at')->nullable();
            // Foreign keys
            $table->unsignedBigInteger('exam_attempt_id');
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');

            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');

            $table->timestamps();

            // Unique constraint
            
            $table->unique(['exam_attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_answers');
    }
};
