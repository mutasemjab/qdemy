<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('banned_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->enum('language', ['ar', 'en', 'both'])->default('both');
             $table->enum('type', [
                'profanity',
                'political',
                'spam',
                'racism',
                'religion',
                'sexual',
                'religious',
                'other'
            ])->default('profanity');

            $table->integer('severity')->default(5); // 1-10
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'language']);
        });
        
        // إضافة عمود للمنشورات والتعليقات
        Schema::table('posts', function (Blueprint $table) {
            $table->json('moderation_flags')->nullable()->after('is_active');
            $table->integer('violation_score')->default(0)->after('moderation_flags');
        });
        
        Schema::table('comments', function (Blueprint $table) {
            $table->json('moderation_flags')->nullable()->after('is_active');
            $table->integer('violation_score')->default(0)->after('moderation_flags');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('banned_words');
        
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['moderation_flags', 'violation_score']);
        });
        
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['moderation_flags', 'violation_score']);
        });
    }
};