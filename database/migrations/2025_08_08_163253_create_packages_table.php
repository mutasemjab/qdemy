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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->float('price', 15, 3)->unsigned();
            $table->text('description')->nullable();
            $table->string('image', 128)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('how_much_course_can_select')->default(1);
            $table->enum('type', ['class', 'lesson'])->default('class');
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
        Schema::dropIfExists('packages');
    }
};
