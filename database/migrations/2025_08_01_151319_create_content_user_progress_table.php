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
            $table->unsignedBigInteger('course_content_id');

            // For video watch tracking
            $table->integer('watch_time')->nullable(); // seconds watched
            $table->boolean('completed')->default(false);

            // For PDF or quizzes: mark as viewed/completed
            $table->timestamp('viewed_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'course_content_id']);
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
