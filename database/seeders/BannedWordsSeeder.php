<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BannedWord;



class BannedWordsSeeder extends Seeder
{
    public function run()
    {
        $bannedWords = [

            // -------------------------
            // 🟥 كلمات بذيئة عربية
            // -------------------------
            ['word' => 'قذر', 'language' => 'ar', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'غبي', 'language' => 'ar', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'حقير', 'language' => 'ar', 'type' => 'profanity', 'severity' => 6],
            ['word' => 'تافه', 'language' => 'ar', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'وسخ', 'language' => 'ar', 'type' => 'profanity', 'severity' => 5],
            ['word' => 'حيوان', 'language' => 'ar', 'type' => 'profanity', 'severity' => 5],
            ['word' => 'كلب', 'language' => 'ar', 'type' => 'profanity', 'severity' => 5],
            ['word' => 'حمار', 'language' => 'ar', 'type' => 'profanity', 'severity' => 5],
            ['word' => 'مجنون', 'language' => 'ar', 'type' => 'profanity', 'severity' => 3],
            ['word' => 'عاهر', 'language' => 'ar', 'type' => 'profanity', 'severity' => 8],
            ['word' => 'قحبة', 'language' => 'ar', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'شرموطة', 'language' => 'ar', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'زانية', 'language' => 'ar', 'type' => 'profanity', 'severity' => 9],
            ['word' => 'لعنة', 'language' => 'ar', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'تفوو', 'language' => 'ar', 'type' => 'profanity', 'severity' => 3],
            ['word' => 'اغبى', 'language' => 'ar', 'type' => 'profanity', 'severity' => 3],
            ['word' => 'يا وسخ', 'language' => 'ar', 'type' => 'profanity', 'severity' => 6],
            ['word' => 'يا خنزير', 'language' => 'ar', 'type' => 'profanity', 'severity' => 6],
            ['word' => 'زفت', 'language' => 'ar', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'يا ابن', 'language' => 'ar', 'type' => 'profanity', 'severity' => 8],
            ['word' => 'حيوان بشري', 'language' => 'ar', 'type' => 'profanity', 'severity' => 6],
            ['word' => 'منحرف', 'language' => 'ar', 'type' => 'profanity', 'severity' => 7],

            // -------------------------
            // 🟦 كلمات بذيئة إنجليزية
            // -------------------------
            ['word' => 'fuck', 'language' => 'en', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'shit', 'language' => 'en', 'type' => 'profanity', 'severity' => 8],
            ['word' => 'bitch', 'language' => 'en', 'type' => 'profanity', 'severity' => 9],
            ['word' => 'asshole', 'language' => 'en', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'bastard', 'language' => 'en', 'type' => 'profanity', 'severity' => 8],
            ['word' => 'dumb', 'language' => 'en', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'jerk', 'language' => 'en', 'type' => 'profanity', 'severity' => 4],
            ['word' => 'idiot', 'language' => 'en', 'type' => 'profanity', 'severity' => 3],
            ['word' => 'dick', 'language' => 'en', 'type' => 'profanity', 'severity' => 9],
            ['word' => 'pussy', 'language' => 'en', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'slut', 'language' => 'en', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'whore', 'language' => 'en', 'type' => 'profanity', 'severity' => 10],
            ['word' => 'moron', 'language' => 'en', 'type' => 'profanity', 'severity' => 5],
            ['word' => 'damn', 'language' => 'en', 'type' => 'profanity', 'severity' => 4],

            // -------------------------
            // 🟨 كلمات سياسية
            // -------------------------
            ['word' => 'داعش', 'language' => 'ar', 'type' => 'political', 'severity' => 9],
            ['word' => 'الإخوان', 'language' => 'ar', 'type' => 'political', 'severity' => 8],
            ['word' => 'القاعدة', 'language' => 'ar', 'type' => 'political', 'severity' => 8],
            ['word' => 'انقلاب', 'language' => 'ar', 'type' => 'political', 'severity' => 5],
            ['word' => 'نظام فاسد', 'language' => 'ar', 'type' => 'political', 'severity' => 5],
            ['word' => 'ثورة', 'language' => 'ar', 'type' => 'political', 'severity' => 4],
            ['word' => 'حزب الله', 'language' => 'ar', 'type' => 'political', 'severity' => 8],
            ['word' => 'إرهابي', 'language' => 'ar', 'type' => 'political', 'severity' => 8],
            ['word' => 'terrorist', 'language' => 'en', 'type' => 'political', 'severity' => 9],
            ['word' => 'isis', 'language' => 'en', 'type' => 'political', 'severity' => 9],
            ['word' => 'hezbollah', 'language' => 'en', 'type' => 'political', 'severity' => 8],
            ['word' => 'revolution', 'language' => 'en', 'type' => 'political', 'severity' => 5],

            // -------------------------
            // 🟩 كلمات سبام (إعلانات وروابط)
            // -------------------------
            ['word' => 'اشترك الآن', 'language' => 'ar', 'type' => 'spam', 'severity' => 4],
            ['word' => 'اربح المال', 'language' => 'ar', 'type' => 'spam', 'severity' => 5],
            ['word' => 'اضغط هنا', 'language' => 'ar', 'type' => 'spam', 'severity' => 4],
            ['word' => 'رابط مباشر', 'language' => 'ar', 'type' => 'spam', 'severity' => 3],
            ['word' => 'حمّل الآن', 'language' => 'ar', 'type' => 'spam', 'severity' => 3],
            ['word' => 'عرض محدود', 'language' => 'ar', 'type' => 'spam', 'severity' => 3],
            ['word' => 'click here', 'language' => 'en', 'type' => 'spam', 'severity' => 4],
            ['word' => 'buy now', 'language' => 'en', 'type' => 'spam', 'severity' => 3],
            ['word' => 'limited offer', 'language' => 'en', 'type' => 'spam', 'severity' => 3],
            ['word' => 'free money', 'language' => 'en', 'type' => 'spam', 'severity' => 5],
            ['word' => 'download free', 'language' => 'en', 'type' => 'spam', 'severity' => 4],
            ['word' => 'make money fast', 'language' => 'en', 'type' => 'spam', 'severity' => 5],

            // -------------------------
            // 🟧 كلمات عنصرية / تمييزية
            // -------------------------
            ['word' => 'عنصري', 'language' => 'ar', 'type' => 'racism', 'severity' => 7],
            ['word' => 'عبد', 'language' => 'ar', 'type' => 'racism', 'severity' => 8],
            ['word' => 'سخيف', 'language' => 'ar', 'type' => 'racism', 'severity' => 4],
            ['word' => 'black monkey', 'language' => 'en', 'type' => 'racism', 'severity' => 10],
            ['word' => 'slave', 'language' => 'en', 'type' => 'racism', 'severity' => 8],
            ['word' => 'nigger', 'language' => 'en', 'type' => 'racism', 'severity' => 10],
            ['word' => 'retard', 'language' => 'en', 'type' => 'racism', 'severity' => 9],

            // -------------------------
            // 🟪 كلمات دينية حساسة
            // -------------------------
            ['word' => 'الله يلعن', 'language' => 'ar', 'type' => 'religious', 'severity' => 10],
            ['word' => 'لعن الدين', 'language' => 'ar', 'type' => 'religious', 'severity' => 10],
            ['word' => 'لعن ربك', 'language' => 'ar', 'type' => 'religious', 'severity' => 10],
            ['word' => 'jesus damn', 'language' => 'en', 'type' => 'religious', 'severity' => 10],
            ['word' => 'god damn', 'language' => 'en', 'type' => 'religious', 'severity' => 10],
            ['word' => 'f*** god', 'language' => 'en', 'type' => 'religious', 'severity' => 10],
        ];

        foreach ($bannedWords as $word) {
            BannedWord::create($word);
        }
    }
}
