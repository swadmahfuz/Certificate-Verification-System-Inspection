<?php

return [
    'app_key' => env('CVS_APP_KEY', 'inspection'),

    'registration_enabled' => env('CVS_REGISTRATION_ENABLED', false),

    'apps' => [
        'training' => 'Training CVS',
        'inspection' => 'Inspection CVS',
        'calibration' => 'Calibration CVS',
        'reports' => 'Reports CVS',
        'certification' => 'BA Certification',
    ],

    'access_levels' => [
        'view' => 'View only',
        'full' => 'Full access',
    ],

    'shared_activity_subject_types' => ['auth', 'user', 'department'],

    'cache_ttl' => [
        'dashboard' => (int) env('CVS_DASHBOARD_CACHE_TTL', 300),
        'permissions' => (int) env('CVS_PERMISSIONS_CACHE_TTL', 900),
    ],

    'certificate_search' => [
        'like' => [
            'certificate_number',
            'inspector',
            'client_name',
            'inspection_type',
            'inspection_location',
            'equipment_name',
        ],
        'exact' => [],
        'date_like' => [
            'inspection_date',
            'validity_date',
        ],
    ],
];
