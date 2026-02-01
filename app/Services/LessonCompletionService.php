<?php

namespace App\Services;

use App\Models\ContentUserProgress;
use App\Models\CourseContent;
use App\Models\Exam;
use App\Models\ExamAttempt;

class LessonCompletionService
{
    /**
     * Check if the video watch threshold is met.
     * A video is considered watched when watch_time >= 90% of video_duration.
     */
    public function isVideoWatched(ContentUserProgress $progress, CourseContent $content): bool
    {
        if (! $content->video_duration || $content->video_duration <= 0) {
            return false;
        }

        return $progress->watch_time >= $content->video_duration * 0.9;
    }

    /**
     * Retrieve the active exam linked to a course content, if any.
     */
    public function getLinkedExam(CourseContent $content): ?Exam
    {
        return $content->exams()->where('is_active', 1)->first();
    }

    /**
     * Check if the user has submitted at least one exam attempt
     * for the given exam, regardless of pass/fail status.
     */
    public function hasExamAttempt(Exam $exam, int $userId): bool
    {
        return ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Determine the completed status for a lesson following deterministic rules:
     *
     * - Video without linked exam:  completed = watch_time >= 90% of video_duration
     * - Video with linked exam:     completed = (watch_time >= 90%) AND (>= 1 exam attempt submitted)
     * - Non-video content:          completed = the persisted completed flag (set on view/download)
     *
     * These rules are hard constraints. The return value is the only authoritative
     * completion state; nothing else may set or override it for video content.
     */
    public function isLessonCompleted(ContentUserProgress $progress, CourseContent $content, int $userId): bool
    {
        if ($content->content_type !== 'video') {
            return (bool) $progress->completed;
        }

        if (! $this->isVideoWatched($progress, $content)) {
            return false;
        }

        $linkedExam = $this->getLinkedExam($content);

        if (! $linkedExam) {
            return true;
        }

        return $this->hasExamAttempt($linkedExam, $userId);
    }

    /**
     * Recalculate and persist the completed flag on a progress record.
     *
     * This is the sanctioned entry point for writing completion state on
     * video content. It evaluates the deterministic rules and writes the
     * result through the model's guarded setter, preventing conflicting states.
     *
     * @return bool The resolved completion status after enforcement.
     */
    public function updateCompletionStatus(ContentUserProgress $progress, CourseContent $content, int $userId): bool
    {
        $completed = $this->isLessonCompleted($progress, $content, $userId);
        $progress->setCompletedFlag($completed);
        $progress->save();

        return $completed;
    }
}
