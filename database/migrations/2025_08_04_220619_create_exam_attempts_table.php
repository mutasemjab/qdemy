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
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->datetime('started_at');
            $table->datetime('submitted_at')->nullable();
            $table->decimal('score', 8, 2)->nullable(); // Total score achieved
            $table->decimal('percentage', 5, 2)->nullable(); // Percentage score
            $table->boolean('is_passed')->nullable(); // Whether the attempt passed
            $table->enum('status', ['in_progress','completed', 'done' ,'abandoned'])
            ->default('in_progress')
            ->comment('in_progress = جاري الاجابة & completed = تم الارسال & done = تم التصحيح سواء يدوي او اليكتروني وظهرت النتيجة  & abandoned = تم تركه ولكن لا يبدو لها فائدة مؤكده اذ ان الامتحان المتروك يتم ارساله اوتوماتيك عند انتهاء الوقت');
            $table->json('question_order')->nullable(); // Order of questions for this attempt
            // Foreign keys
            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_attempts');
    }
};
