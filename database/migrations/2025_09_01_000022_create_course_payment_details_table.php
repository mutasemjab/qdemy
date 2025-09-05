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
        Schema::create('course_payment_details', function (Blueprint $table) {
          $table->id();
          $table->foreignId('user_id')->nullable();
          $table->foreignId('course_id')->nullable();
          $table->foreignId('teacher_id')->nullable();
          $table->decimal('amount', 10, 2)->nullable();
          $table->text('notes')->nullable();

          $table->timestamps();

          $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
          $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
          $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');

          $table->index('user_id');
          $table->index('course_id');
          $table->index('teacher_id');
          $table->index(['user_id', 'course_id']);
          $table->index(['user_id', 'teacher_id']);
        });
    }

  public function down()
  {
    Schema::dropIfExists('course_payment_details');
  }
};
