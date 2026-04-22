<?php

return [
    'iclock_secret' => env('ATTENDANCE_ICLOCK_SECRET'),
    'require_user_identity' => env('ATTENDANCE_REQUIRE_USER_IDENTITY', false),
    'require_user_verified' => env('ATTENDANCE_REQUIRE_USER_VERIFIED', false),
];
