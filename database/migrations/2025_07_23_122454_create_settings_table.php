<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->text('text_under_logo_in_footer');
            $table->string('email');
            $table->string('phone');
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('address')->nullable();
            $table->string('google_play_link')->nullable();
            $table->string('app_store_link')->nullable();
            $table->string('hawawi_link')->nullable();
            $table->string('min_version_google_play')->nullable();
            $table->string('min_version_app_store')->nullable();
            $table->string('min_version_hawawi')->nullable();
            //
            $table->string('number_of_course')->nullable();
            $table->string('number_of_teacher')->nullable();
            $table->string('number_of_viewing_hour')->nullable();
            $table->string('number_of_students')->nullable();
            $table->enum('pos_commission_distribution', ['50_50', '100_teacher', '100_platform'])->default('50_50');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'logo' => 'logo.png',
            'text_under_logo_in_footer' => 'Lorem ipsum dolor sit amet consectetur. Porttitor molestie sapien dictum quam semper a sed auctor turpis. Quam iaculis fringilla eros erat. Purus dui aliquet eget blandit enim nunc accumsan quis. Ut suscipit sed nunc magna condimentum mollis sed. Mauris eu convallis orci posuere imperdiet elit platea id lectus. Et nibh volutpat velit velit amet.',
            'email' => 'info@example.com',
            'phone' => '+1234567890',
            'address' => '123 Main Street, City, Country',
            'google_play_link' => 'https://play.google.com/store/apps/details?id=com.example.app',
            'app_store_link' => 'https://apps.apple.com/app/id1234567890',
            'hawawi_link' => 'https://appgallery.huawei.com/#/app/C123456',
            'min_version_google_play' => '1.0.0',
            'min_version_app_store' => '1.0.0',
            'min_version_hawawi' => '1.0.0',
            'number_of_course' => '+20 Thousand',
            'number_of_teacher' => '+1 Thousand',
            'number_of_viewing_hour' => '+2 Million',
            'number_of_students' => '+3 Million',
            'pos_commission_distribution' => '50_50',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
