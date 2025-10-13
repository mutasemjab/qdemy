<?php

namespace App\Observers;

use App\Models\Doseyat;
use App\Services\FollowerNotificationService;

class DoseyatObserver
{
    public function created(Doseyat $doseyat)
    {
        if ($doseyat->teacher_id) {
            FollowerNotificationService::notifyNewDoseyat($doseyat);
        }
    }
}