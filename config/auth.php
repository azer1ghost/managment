<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'permissions' => [
        'generally',
        'signature',
        'department-chief',
        'viewAny-log',
        'viewAny-account', 'manage-account',
        'viewAny-advertising', 'view-advertising', 'manage-advertising', 'update-advertising', 'delete-advertising',
        'viewAny-asanImza', 'view-asanImza', 'manage-asanImza', 'delete-asanImza',
        'viewAll-announcement', 'viewAny-announcement', 'view-announcement', 'create-announcement', 'update-announcement', 'delete-announcement',
        'viewAny-calendar', 'create-calendar', 'update-calendar', 'delete-document',
        'viewAll-client', 'viewAny-client', 'view-client', 'create-client', 'update-client', 'delete-client', 'forceDelete-client', 'restore-client', 'canUploadContract-client',
        'viewAny-customerEngagement', 'view-customerEngagement', 'manage-customerEngagement', 'delete-customerEngagement',
        'viewAny-company', 'view-company', 'create-company', 'update-company', 'delete-company',
        'viewAny-conference', 'view-conference', 'create-conference', 'update-conference', 'delete-conference',
        'viewAny-department', 'view-department', 'manage-department',
        'viewAny-document', 'view-document', 'create-document', 'update-document', 'delete-document',
        'viewAll-inquiry', 'viewAllDepartment-inquiry', 'viewAny-inquiry', 'view-inquiry', 'create-inquiry', 'update-inquiry', 'delete-inquiry', 'forceDelete-inquiry', 'restore-inquiry', 'editAccessToUser-inquiry',
        'viewAny-meeting', 'view-meeting', 'create-meeting', 'update-meeting', 'delete-meeting',
        'viewAny-notification', 'view-notification', 'manage-notification',
        'viewAny-option', 'view-option', 'manage-option',
        'viewAny-parameter', 'view-parameter', 'manage-parameter',
        'viewAny-position', 'view-position', 'manage-position',
        'viewAny-referral', 'view-referral', 'manage-referral',
        'viewAll-report', 'viewAny-report', 'generateReports-report', 'showSubReports-report', 'showSubReport-report', 'generateSubReport-report', 'updateSubReport-report', 'delete-report',
        'viewAny-role', 'view-role', 'manage-role',
        'viewAny-service', 'view-service', 'create-service', 'update-service', 'delete-service',
        'viewAll-task', 'viewAny-task', 'view-task', 'create-task', 'update-task', 'delete-task', 'forceDelete-task', 'restore-task',
        'viewAny-update', 'view-update', 'create-update', 'update-update', 'delete-update',
        'viewAny-user', 'view-user', 'create-user', 'update-user', 'delete-user','manageStatus-user', 'manageReferral-user',
        'viewAny-widget',
        'inquiryStatus-widget',
        'inquiryDaily-widget',
        'taskDone-widget',
        'bonusTotal-widget',
        'client-widget',
        'workMonthly-widget',
        'workPersonal-widget',
        'viewAll-work', 'viewAny-work', 'view-work', 'create-work', 'update-work', 'delete-work', 'canVerify-work', 'canRedirect-work', 'viewAllDepartment-work',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
