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
        Schema::create('question_websites', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->enum('type',['all','register','payment','card','courses','technical','privacy','account'])->default('all');
            $table->timestamps();
        });

        DB::table('question_websites')->insert([
            'question' => 'What is your cancellation policy?', 
            'answer' => 'You can now cancel an order when it is in packed/shipped status.', 
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_websites');
    }
};
