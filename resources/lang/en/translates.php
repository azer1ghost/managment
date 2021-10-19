<?php

return [


    'parameters' => [
        'types' => [
            'contact_method' => 'Contact Method',
            'subject' => 'Subject',
            'source' => 'Source',
            'kind' => 'Kind',
            'operation' => 'Operation',
            'status' => 'Status',
        ],
    ],

    'filters' => [
        'date' => 'Filter by Date',
        'code' => 'Filter by Code',
        'subject' => 'Filter by Subject',
        'company' => 'Filter by Company',
        'client_name' => 'Filter by Client name',
        'phone' => 'Filter by Phone',
        'email' => 'Filter by Email',
        'written_by' => 'Filter by Written by',
        'source' => 'Filter by Source',
        'contact_method' => 'Filter by Contact Method',
        'clear' => 'Clear',
        'select' => 'Not Selected',
        'or' => "Filter by :first, :second or :third",
    ],

    'placeholders' => [
        'serial_pattern' => 'Enter serial number',
        'fin' => 'Enter FIN',
        'range' => 'Enter date range',
        'code' => 'Enter code',
        'note' => 'Enter note',
        'fullname' => 'Enter fullname',
        'phone' => 'Enter phone',
        'phone_coop' => 'Enter cooperative phone',
        'address' => 'Enter address',
        'choose' => 'Choose',
        'name'   => 'Enter name',
        'surname'   => 'Enter surname',
        'father'   => 'Enter father\'s name',
        'mail_coop'   => 'Enter cooperative email',
        'mail'   => 'Enter email',
        'password' => 'Enter password',
        'password_confirm' => 'Enter password confirmation',
        'or' => "Enter :first, :second or :third",

    ],

    'fields' => [
        'default_lang' => 'Default language',
        'personal' => 'PERSONAL',
        'employment' => 'EMPLOYMENT',
        'passport' => 'PASSPORT',
        'contact' => 'CONTACT',
        'address_title' => 'ADDRESS',
        'user' => 'User',
        'department' => 'Department',
        'position' => 'Position',
        'mgCode' => 'MG Code',
        'date' => 'Date',
        'time' => 'Time',
        'company' => 'Company',
        'clientName' => 'Client Name',
        'writtenBy' => 'Written By',
        'subject' => 'Subject',
        'actions' => 'Actions',
        'contactMethod' => 'Contact method',
        'phone' => 'Phone',
        'phone_private' => 'Personal phone',
        'phone_coop' => 'Cooperative phone',
        'email_coop' => 'Cooperative Email',
        'email_private' => 'Personal Email',
        'country' => 'Country',
        'city' => 'City',
        'password' => 'Password',
        'password_confirm' => 'Password Confirmation',
        'role' => 'Role',
        'note' => 'Note',
        'fullname' => 'Fullname',
        'client' => 'Client',
        'logo' => 'Logo',
        'name' => 'Name',
        'surname' => 'Surname',
        'father' => 'Father\'s name',
        'serial' => 'Serial',
        'gender' => 'Gender',
        'birthday' => 'Birthday',
        'address' => 'Address',
        'website' => 'Website',
        'mobile' => 'Mobile',
        'mail' => 'Email',
        'call_center' => 'Call center',
        'keywords' => 'Keywords',
        'about' => 'About',
        'priority' => [
            'key' => 'Priority',
            'options'  => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'urgent' => 'Urgent'
            ]
        ],
        'status' => [
            'key' => 'Status',
            'options'  => [
                'to_do' => 'To do',
                'in_progress' => 'In progress',
                'done' => 'Done'
            ]
        ]
    ],

    'buttons' => [
        'create' => 'Create',
        'save'   => 'Save',
        'back'   => 'Back',
        'search' => 'Search',
        'filter' => 'Filter',
        'add'    => 'Add',
        'copy'   => 'Copy',
        'copied' => 'Copied',
        'previous' => 'Previous',
        'next' => 'Next',
        'change' => 'Change',
    ],

    'or' => 'or',

    'navbar' => [
        'general'    => 'General',
        'dashboard'  => 'Dashboard',
        'welcome'    => 'Welcome',
        'cabinet'    => 'Cabinet',
        'company'    => 'Companies',
        'account'    => 'Account',
        'signature'  => 'Signature',
        'inquiry'    => 'Inquiries',
        'task'       => 'Tasks',
        'parameter'  => 'Parameters',
        'option'     => 'Options',
        'role'       => 'Roles',
        'user'       => 'Users',
        'department' => 'Departments',
        'position'   => 'Positions',
        'notification' => 'Notifications',
        'client'     => 'Clients',
        'referral'  => 'Referrals',
        'bonus'     => 'Bonuses',
        'update'     => 'Updates'
    ],

    'date' => [
        'today' => 'Today',
        'month' => 'This month'
    ],

    'register' => [
        'register'   => 'Register',
        'fill' => 'Fill all form fields to go to next step',
        'progress' => [
            'language' => 'Language',
            'account' => 'Account',
            'personal' => 'Personal',
            'avatar' => 'Avatar'
        ],
        'steps' => 'Step :step - 4',
        'title'      => 'Register as an employee',
        'name'       => 'Name',
        'surname'    => 'Surname',
        'mail_coop'  => 'Cooperative Email',
        'mail'       => 'Email',
        'phone'       => 'Personal phone',
        'department' => 'Department',
        'company'    => 'Company',
        'password'   => 'Password',
        'password_confirm' => 'Password confirmation',
    ],

    'login' => [
        'login'         => 'Sign into your account',
        'remember'      => 'Remember me',
        'forgot_pwd'    => 'Forgot Your Password?',
        'no_account'    => "Don't have an account?",
        'register_here' => 'Register here'
    ],

    'flash_messages' => [
        'inquiry_status_updated' => [
            'title' => ':code Updated',
            'msg'   => 'Status updated from :prev to :next'
        ],
        'task_status_updated' => [
            'confirm' => ['title' => 'Update', 'msg' => 'Are you sure to change status from :prev to :next?'],
            'title' => ':name Yeniləndi',
            'msg'   => 'Status updated from :prev to :next'
        ]
    ],

    'loading' => 'Loading',

    'total_items' => 'Showing :count of :total',

    'access' => 'Access',

    'disabled' => 'Inactive',

    'logout' => 'Logout',

    'countries' => [
        'Azerbaijan' => 'Azerbaijan',
        'Turkey' => 'Turkey'
    ],

    'cities' => [
        'Baku' => 'Baku',
        'Sumgayit' => 'Sumgayit'
    ],

    'gender' => [
        'male' => 'Male',
        'female' => 'Female'
    ],

    'comments' => [
        'new' => 'You have new comment'
    ],

    'inquiries' => [
        'types' => [
            'from_us' => 'From us',
            'from_customers' => 'From customers'
        ],
        'label' => 'Inquiry type',
    ],

    'tasks' => [
        'created' => "New task :name added successfully. <p>Please now assign some To do.</p>",
        'not_started' => 'Not started',
        'new' => 'You have new task assigned',
        'content' => [
            'user' => 'New task assigned to you',
            'department' => 'New task assigned to your department'
        ],
        'list' => [
            'to_do' => 'To do',
            'placeholder' => 'What should be done ?',
            'new' => 'You have new list in task you are assigned to'
        ],
        'types' => [
            'assigned_to_me' => 'Assigned to me',
            'my_tasks' => 'My tasks',
            'all' => 'All'
        ]
    ],

    'referrals' => [
        'link' => 'Your Referral link',
        'sub_message' => 'This is your referral link, copy and share with your friends',
        'total' => 'Total Referrals',
        'packages' => 'Packages',
        'earnings'  => 'Earnings',
        'bonus'   => 'Bonus balance',
        'get_data' => 'For getting referral data',
        'send_req' => 'Send request',
        'updated' => 'Last updated'
    ],

    'updates' => [
        'Rejected',
        'Pending',
        'Accepted',
        'Started',
        'Done',
        'Upcoming',
        'Error',
        'Bug',
        'Fixed'
    ]
];
