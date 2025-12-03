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
            $table->string('question_en');
            $table->string('question_ar');
            $table->text('answer_en');
            $table->text('answer_ar');
            $table->enum('type',['all','register','payment','card','courses','technical','privacy','account'])->default('all');
            $table->timestamps();
        });

        DB::table('question_websites')->insert([
            'question_en' => 'What is your cancellation policy?', 
            'question_ar' => 'What is your cancellation policy?', 
            'answer_en' => 'You can now cancel an order when it is in packed/shipped status.', 
            'answer_ar' => 'You can now cancel an order when it is in packed/shipped status.', 
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
