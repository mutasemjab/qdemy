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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');                             // Arabic name
            $table->string('name_en')->nullable();                 // English name
            $table->string('ctg_key')->nullable();                 // the same as english name without space
            $table->string('level')->nullable();                   // like tawjihi_program_subject || semester || school_sbjects
            $table->text('description_ar')->nullable();            // Arabic description
            $table->text('description_en')->nullable();            // English description
            $table->string('icon')->nullable();                    // Font Awesome icon class
            $table->string('color')->default('#007bff');         // Category color
            $table->integer('sort_order')->default(0);             // For ordering
            $table->boolean('is_active')->default(true);           // Active status
            $table->unsignedBigInteger('parent_id')->nullable();   // Self-referencing for hierarchy
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->enum('type', ['class', 'lesson','major'])->default('class');

            $table->boolean('is_ediatble')->default(true);

            // if_category.type == lesson
            $table->boolean('is_optional')->default(false);                    // if optional or non optional subject
            $table->boolean('is_ministry')->default(true);                     // if optional or non optional subject
            $table->string('field_type')->nullable()->comment('scientific || literary || general'); // health || engineering || litrature || technology || law || buisness || general
            $table->string('optional_form_field_type')->nullable()->comment('scientific || literary || general'); // health || engineering || litrature || technology || law || buisness || general

            $table->timestamps();

            // Indexes for better performance
            $table->index(['parent_id', 'is_active', 'sort_order']);
            $table->index('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
