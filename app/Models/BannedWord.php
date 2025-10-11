<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BannedWord extends Model
{
    protected $fillable = [
        'word',
        'language',
        'type',
        'severity',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'severity' => 'integer',
    ];
    
    /**
     * 🔥 الحصول على جميع الكلمات المحظورة (بدون Cache مؤقتاً للاختبار)
     */
    public static function getActiveBannedWords()
    {
        // 🔥 بدون Cache مؤقتاً لنرى إذا المشكلة في Cache
        $words = self::where('is_active', true)->get();
        
        Log::info('Banned words loaded (NO CACHE)', [
            'count' => $words->count(),
            'words_sample' => $words->take(5)->pluck('word')
        ]);
        
        return $words;
    }
    
    /**
     * مسح الـ Cache عند التعديل
     */
    protected static function boot()
    {
        parent::boot();
        
        static::saved(function () {
            Cache::forget('banned_words_active');
        });
        
        static::deleted(function () {
            Cache::forget('banned_words_active');
        });
    }
}