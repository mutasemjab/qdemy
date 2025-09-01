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
            $table->text('description_ar')->nullable();            // Arabic description
            $table->text('description_en')->nullable();            // English description

            $table->string('icon')->nullable();                    // Font Awesome icon class
            $table->string('color')->default('#007bff');         // Category color
            $table->integer('sort_order')->default(0);             // For ordering
            $table->boolean('is_active')->default(true);           // Active status

            $table->string('ctg_key')->nullable()->comment("auto generate");                 // the same as english name without space
            $table->string('level')->nullable()->comment("auto generate");                   // like tawjihi_program_subject || semester || school_sbjects

            $table->json('parents')->nullable()->comment("auto generate - tree of parents");   // tree of parents
            $table->json('childrens')->nullable()->comment("auto generate - tree of childrens"); // tree of childrens

            $table->unsignedBigInteger('parent_id')->nullable()->comment("direct parent");   // Self-referencing for hierarchy
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->enum('type', ['class', 'major'])->comment("not editable")->default('class');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['parent_id', 'is_active', 'sort_order','ctg_key']);
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
