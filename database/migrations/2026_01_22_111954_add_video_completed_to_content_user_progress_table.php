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
        Schema::table('content_user_progress', function (Blueprint $table) {
            $table->boolean('video_completed')->default(false)->after('watch_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_user_progress', function (Blueprint $table) {
            $table->dropColumn('video_completed');
        });
    }
};
