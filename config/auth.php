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
        'structure',
        'signature',
        'department-chief',
        'viewAny-log',
        'viewAny-account', 'manage-account',
        'viewAny-accessRate' , 'view-accessRate', 'create-accessRate', 'update-accessRate','manage-accessRate','delete-accessRate',
        'viewAny-advertising', 'view-advertising', 'manage-advertising', 'update-advertising', 'delete-advertising',
        'viewAny-asanImza', 'view-asanImza', 'manage-asanImza', 'delete-asanImza',
        'viewAny-fund', 'view-fund', 'manage-fund', 'delete-fund',
        'viewAll-announcement', 'viewAny-announcement', 'view-announcement', 'create-announcement', 'update-announcement', 'delete-announcement',
        'viewAll-barcode', 'viewAllDepartment-barcode', 'viewAny-barcode', 'view-barcode', 'create-barcode', 'update-barcode', 'delete-barcode',
        'viewAny-calendar', 'create-calendar', 'update-calendar','delete-calendar',
        'viewAny-certificate', 'manage-certificate', 'delete-certificate',
        'viewAll-client', 'viewAny-client', 'view-client', 'create-client', 'update-client', 'delete-client', 'forceDelete-client', 'restore-client', 'canUploadContract-client', 'canAssignUsers-client', 'canExport-client', 'satisfactionMeasure-client',
        'viewAny-customerEngagement', 'view-customerEngagement', 'manage-customerEngagement', 'delete-customerEngagement',
        'viewAny-company', 'view-company', 'create-company', 'update-company', 'delete-company',
        'viewAny-command', 'view-command', 'create-command', 'update-command', 'delete-command',
        'viewAny-creditor', 'view-creditor', 'create-creditor', 'update-creditor', 'delete-creditor',
        'viewAny-isoDocument', 'view-isoDocument', 'create-isoDocument', 'update-isoDocument', 'delete-isoDocument',
        'viewAny-change', 'view-change', 'manage-change', 'delete-change',
        'viewAny-conference', 'view-conference', 'manage-conference',
        'viewAny-department', 'view-department', 'manage-department',
        'viewAny-document', 'view-document', 'create-document', 'update-document', 'delete-document',
        'viewAny-financeClient', 'view-financeClient', 'create-financeClient', 'update-financeClient', 'delete-financeClient',
        'viewAny-folder' , 'view-folder', 'create-folder', 'update-folder','manage-folder','delete-folder',
        'viewAny-protocol' , 'view-protocol', 'create-protocol', 'update-protocol','manage-protocol','delete-protocol',
        'viewAny-sentDocument' , 'view-sentDocument', 'create-sentDocument', 'update-sentDocument','manage-sentDocument','delete-sentDocument',
        'viewAny-supplier' , 'view-supplier', 'create-supplier', 'update-supplier','manage-supplier','delete-supplier',
        'viewAny-employeeSatisfaction', 'view-employeeSatisfaction', 'manage-employeeSatisfaction', 'delete-employeeSatisfaction', 'measure-employeeSatisfaction',
        'viewAny-jobInstruction', 'viewAll-jobInstruction', 'viewAllDepartment-jobInstruction', 'view-jobInstruction', 'create-jobInstruction', 'update-jobInstruction','manage-jobInstruction','delete-jobInstruction',
        'viewAny-internalNumber', 'view-internalNumber', 'create-internalNumber', 'update-internalNumber','manage-internalNumber','delete-internalNumber',
        'viewAny-internalRelation', 'view-internalRelation', 'create-internalRelation', 'update-internalRelation','manage-internalRelation','delete-internalRelation',
        'viewAny-internalDocument' , 'view-internalDocument', 'create-internalDocument', 'update-internalDocument','manage-internalDocument','delete-internalDocument',
        'viewAll-inquiry', 'viewAllDepartment-inquiry', 'viewAny-inquiry', 'view-inquiry', 'create-inquiry', 'update-inquiry', 'delete-inquiry', 'forceDelete-inquiry', 'restore-inquiry', 'editAccessToUser-inquiry','checkRejectedReason-inquiry',
        'viewAny-logistics', 'view-logistics', 'create-logistics', 'update-logistics', 'delete-logistics',
        'viewAny-logisticClient', 'view-logisticClient', 'create-logisticClient', 'update-logisticClient', 'delete-logisticClient',
        'viewAny-meeting', 'view-meeting', 'create-meeting', 'update-meeting', 'delete-meeting',
        'viewAny-salesInquiry', 'viewAll-salesInquiry',
        'viewAny-notification', 'view-notification', 'manage-notification',
        'viewAny-option', 'view-option', 'manage-option',
        'viewAny-organization', 'view-organization', 'manage-organization','delete-organization',
        'viewAny-parameter', 'view-parameter', 'manage-parameter',
        'viewAny-partner', 'view-partner', 'manage-partner',
        'viewAny-position', 'view-position', 'manage-position',
        'viewAny-registrationLog', 'view-registrationLog', 'manage-registrationLog', 'delete-registrationLog',
        'viewAny-referral', 'view-referral', 'manage-referral',
        'viewAll-report', 'viewAny-report', 'generateReports-report', 'showSubReports-report', 'showSubReport-report', 'generateSubReport-report', 'updateSubReport-report', 'delete-report',
        'viewAny-role', 'view-role', 'manage-role',
        'viewAny-satisfaction', 'view-satisfaction', 'create-satisfaction', 'update-satisfaction', 'delete-satisfaction',
        'viewAny-questionnaire', 'view-questionnaire', 'create-questionnaire', 'update-questionnaire', 'delete-questionnaire',
        'viewAny-service', 'view-service', 'create-service', 'update-service', 'delete-service',
        'viewAny-salesClient', 'view-salesClient', 'manage-salesClient', 'delete-salesClient',
        'viewAny-support', 'view-support', 'create-support', 'update-support', 'delete-support',
        'viewAny-supplier', 'view-supplier', 'create-supplier', 'update-supplier', 'delete-supplier',
        'viewAny-salary', 'view-salary', 'create-salary', 'update-salary', 'delete-salary',
        'viewAny-salesActivityType', 'view-salesActivityType', 'manage-salesActivityType',
        'viewAll-task', 'viewAllDepartment-task', 'viewAny-task', 'view-task', 'create-task', 'update-task', 'delete-task', 'forceDelete-task', 'restore-task', 'redirect-task',
        'viewAny-transaction', 'view-transaction', 'manage-transaction',
        'viewAny-salesActivity', 'view-salesActivity', 'manage-salesActivity',
        'viewAny-statement', 'view-statement', 'manage-statement',
        'viewAny-update', 'view-update', 'create-update', 'update-update', 'delete-update',
        'viewAny-user', 'view-user', 'create-user', 'update-user', 'delete-user','manageStatus-user', 'manageReferral-user',
        'viewAll-work', 'viewAny-work', 'view-work', 'create-work', 'update-work', 'delete-work', 'canVerify-work', 'canRedirect-work', 'viewAllDepartment-work', 'viewPrice-work', 'editPrice-work', 'editTable-work', 'canPlanned-work',
        'viewAny-widget',
        'inquiryStatus-widget',
        'inquiryDaily-widget',
        'inquiryPersonalDaily-widget',
        'inquiryPersonalMonthly-widget',
        'inquiryUser-widget',
        'taskDone-widget',
        'bonusTotal-widget',
        'client-widget',
        'workMonthly-widget',
        'userWorkMonthly-widget',
        'workPersonal-widget',
        'service-widget',
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

        'clients' => [
            'driver' => 'session',
            'provider' => 'clients',
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
        'clients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Client::class,
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
