<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Email verification window (hours)
    |--------------------------------------------------------------------------
    |
    | Unverified accounts older than this are removed. Admins (role = 1) are never
    | deleted by this logic.
    |
    */

    'verification_ttl_hours' => (int) env('REGISTRATION_VERIFICATION_TTL_HOURS', 24),

];
