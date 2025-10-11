<?php

namespace App\Services;

use App\Models\BannedWord;
use Illuminate\Support\Facades\Log;

class ContentModerationService
{
    public function moderate($content)
    {
        // 🔥 الحصول على الكلمات مباشرة من DB
        $bannedWords = \DB::table('banned_words')
            ->where('is_active', true)
            ->get();
        
        $violations = [];
        $totalScore = 0;
        
        $content = strtolower(trim($content));
        
        Log::info('🔍 Starting moderation', [
            'content' => $content,
            'banned_words_count' => $bannedWords->count()
        ]);
        
        foreach ($bannedWords as $bannedWord) {
            $word = strtolower(trim($bannedWord->word));
            
            if (empty($word)) {
                continue;
            }
            
            // 🔥 فحص بسيط بدون شرط اللغة
            $position = strpos($content, $word);
            
            if ($position !== false) {
                $violations[] = [
                    'word' => $bannedWord->word,
                    'type' => $bannedWord->type,
                    'severity' => $bannedWord->severity,
                    'language' => $bannedWord->language,
                    'position' => $position,
                ];
                
                $totalScore += $bannedWord->severity;
                
                Log::warning('🚫 Banned word detected!', [
                    'word' => $word,
                    'position' => $position,
                    'severity' => $bannedWord->severity
                ]);
            }
        }
        
        $isClean = empty($violations);

        $shouldApprove = $isClean;
        
        Log::info('✅ Moderation complete', [
            'is_clean' => $isClean,
            'violations_count' => count($violations),
            'total_score' => $totalScore,
        ]);
        
        return [
            'is_clean' => $isClean,
            'should_approve' => $shouldApprove,
            'violations' => $violations,
            'violation_score' => $totalScore,
        ];
    }
    
    public function clean($content, $replacement = '***')
    {
        $bannedWords = \DB::table('banned_words')
            ->where('is_active', true)
            ->get();
        
        $cleanedContent = $content;
        
        foreach ($bannedWords as $bannedWord) {
            $word = trim($bannedWord->word);
            if (empty($word)) continue;
            
            $cleanedContent = str_ireplace($word, $replacement, $cleanedContent);
        }
        
        return $cleanedContent;
    }
}