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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('role_name', ['student', 'parent', 'teacher'])->default('student');
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->text('fcm_token')->nullable();
            $table->text('ip_address')->nullable();
            $table->text('last_login')->nullable();
            $table->tinyInteger('activate')->default(1); // 1 yes //2 no
            $table->double('balance')->default(0);
            $table->string('referal_code')->nullable();
            $table->string('photo')->nullable();

            $table->unsignedBigInteger('clas_id')->nullable(); // الصف التابع له
            $table->foreign('clas_id')->references('id')->on('clas')->onDelete('cascade');

            // for social login
            $table->text('google_id')->nullable();
            $table->text('apple_id')->nullable();
            $table->text('access_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
