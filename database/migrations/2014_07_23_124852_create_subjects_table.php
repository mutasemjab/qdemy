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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');                               // Arabic name
            $table->string('name_en')->nullable();                   // English name
            $table->text('description_ar')->nullable();              // Arabic description
            $table->text('description_en')->nullable();              // English description
            // $table->string('field_type')->nullable()->comment('require if related to categories.ctg_key=final_year- scientific-fields || literary-fields');
            $table->string('icon')->nullable();                      // Font Awesome icon class
            $table->string('color')->default('#007bff');           // Category color
            $table->integer('sort_order')->default(0);               // For ordering
            $table->boolean('is_active')->default(true);             // Active status

            // $table->boolean('is_optional')->default(false);                    // if optional or non optional subject
            // $table->boolean('is_ministry')->default(true);                     // if ministry or school subject

            $table->foreignId('field_type_id')->nullable()->comment('require if related to categories.ctg_key=final_year- scientific-fields || literary-fields');
            $table->foreign('field_type_id')->references('id')->on('categories')->onDelete('set null');

            // direct grade year
            $table->unsignedBigInteger('grade_id')->nullable()->comment('not editable - direct grade year');
            $table->foreign('grade_id')->references('id')->on('categories')->onDelete('cascade');

            // category semester id if exist
            $table->unsignedBigInteger('semester_id')->nullable()->comment('not editable - category semester id if exist');
            $table->foreign('semester_id')->references('id')->on('categories')->onDelete('cascade');

            $table->unsignedBigInteger('programm_id')->nullable()->comment('not editable - root category id (programm)');   // root category id (programm)
            $table->foreign('programm_id')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['programm_id','grade_id', 'semester_id','field_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
};
