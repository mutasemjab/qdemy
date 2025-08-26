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
        Schema::create('course_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_ar');

            $table->enum('content_type', ['video', 'pdf', 'quiz', 'assignment'])->default('video');

            // Common fields
            $table->tinyInteger('is_free')->default(1); // 1 FREE  // 2 Paid
            $table->integer('order')->default(0); // order in course

            // Video specific
            $table->enum('video_type', ['youtube', 'bunny'])->nullable(); // if video
            $table->string('video_url')->nullable();
            $table->integer('video_duration')->nullable(); // seconds

            // PDF specific
            $table->string('file_path')->nullable();
            $table->enum('pdf_type', ['homework', 'worksheet', 'notes', 'other'])->nullable();

            // releation
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->references('id')->on('course_sections')->onDelete('cascade');

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
        Schema::dropIfExists('course_contents');
    }
};
