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
        Schema::create('content_user_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_content_id')->nullable();

            // For video watch tracking
            $table->integer('watch_time')->nullable(); // seconds watched
            $table->boolean('completed')->default(false);

            // For PDF or quizzes: mark as viewed/completed
            $table->timestamp('viewed_at')->nullable();

            // new for exam progress
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('exam_attempt_id')->nullable();
            
            // Exam results
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->boolean('is_passed')->default(false);
            
            // Add foreign keys
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('set null');
            
        
            $table->timestamps();

            $table->index(['user_id', 'course_content_id']);
            $table->index(['user_id', 'exam_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_content_id')->references('id')->on('course_contents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_user_progress');
    }
};
