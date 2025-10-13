<?php

namespace App\Observers;

use App\Models\Exam;
use App\Services\FollowerNotificationService;

class ExamObserver
{
    public function created(Exam $exam)
    {
        // Only notify if exam is active and has a creator
        if ($exam->created_by && $exam->is_active) {
            FollowerNotificationService::notifyNewExam($exam);
        }
    }
}