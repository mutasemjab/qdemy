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
        Schema::create('course_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable();
            $table->integer('course_no')->nullable();

            $table->string('currency', 3)->default(CURRENCY);

            $table->string('transaction_reference', 255)->nullable();
            $table->json('gateway_response')->nullable();
            $table->string('gateway_name')->nullable();
            $table->string('payment_method')->default('card')->nullable()->comment('card|visa|cash');
            $table->string('status')->default('completed');
            $table->decimal('sum_amount', 10, 2);

            $table->string('receipt_number', 100)->nullable();
            $table->string('invioced_date', 100)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('course_payments');
    }

};
