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
        Schema::create('ministerial_years_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->string('pdf')->nullable();
            $table->string('pdf_size')->nullable();
            $table->string('display_name')->nullable();
            
            // Add download counter
            $table->integer('download_count')->default(0);
            
            // Add active status
            $table->boolean('is_active')->default(true);
            
            // Add sort order
            $table->integer('sort_order')->default(0);
            
            // Add indexes for better performance
            $table->index(['category_id', 'subject_id', 'is_active']);
            $table->index(['created_at']);
            $table->index(['sort_order']);
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
        Schema::dropIfExists('ministerial_years_questions');
    }
};
