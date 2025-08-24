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
        Schema::create('opinion_students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('number_of_star');
            $table->string('photo');
            $table->string('title');
            $table->text('description');
            $table->timestamps();
        });

         DB::table('opinion_students')->insert([
            'name' => 'Mutasem', 
            'number_of_star' => 4.5, 
            'photo' => 'logo.png', 
            'title' => 'good platform', 
            'description' => 'i love this platform', 
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opinion_students');
    }
};
