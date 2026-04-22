<?php

namespace App\Observers;

use App\Models\AttendanceIdentity;
use App\Services\ZKTeco\IClockCommandQueue;

class AttendanceIdentityObserver
{
    public function updated(AttendanceIdentity $identity): void
    {
        if ($identity->kind !== 'user') {
            return;
        }

        $devicePin = (string) $identity->device_pin;
        if ($devicePin === '') {
            return;
        }

        $becameInactive = $identity->wasChanged('is_active') && !$identity->is_active;
        $lostUser = $identity->wasChanged('user_id') && $identity->user_id === null;

        if ($becameInactive || $lostUser) {
            app(IClockCommandQueue::class)->enqueueDeleteUserByPin($devicePin);
        }
    }

    public function deleted(AttendanceIdentity $identity): void
    {
        if ($identity->kind !== 'user') {
            return;
        }

        $devicePin = (string) $identity->device_pin;
        if ($devicePin === '') {
            return;
        }

        app(IClockCommandQueue::class)->enqueueDeleteUserByPin($devicePin);
    }
}
