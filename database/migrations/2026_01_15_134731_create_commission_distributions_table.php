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
        Schema::create('commission_distributions', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->unsignedBigInteger('course_payment_id')->nullable();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('pos_id')->nullable();

            // Course & Price Information
            $table->decimal('course_price', 10, 2);

            // Platform Commission
            $table->decimal('platform_commission_percentage', 5, 2);
            $table->decimal('platform_commission_amount', 10, 2);

            // POS Commission
            $table->decimal('pos_commission_percentage', 5, 2);
            $table->decimal('pos_commission_amount', 10, 2);

            // Distribution Configuration
            $table->enum('distribution_type', ['50_50', '100_teacher', '100_platform']);

            // Final Amounts (after deductions)
            $table->decimal('platform_final_amount', 10, 2);
            $table->decimal('teacher_final_amount', 10, 2);

            // Deduction Breakdown
            $table->decimal('platform_pos_deduction', 10, 2)->comment('Amount deducted from platform due to POS commission');
            $table->decimal('teacher_pos_deduction', 10, 2)->comment('Amount deducted from teacher due to POS commission');

            // Additional Info
            $table->text('notes')->nullable();

            // Indexes
            $table->foreign('course_payment_id')->references('id')->on('course_payments')->onDelete('set null');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pos_id')->references('id')->on('p_o_s')->onDelete('set null');

            $table->index('course_payment_id');
            $table->index('teacher_id');
            $table->index('course_id');

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
        Schema::dropIfExists('commission_distributions');
    }
};
