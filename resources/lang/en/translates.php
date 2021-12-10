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
        'clear' => 'Clear filters',
        'select' => 'Not Selected',
        'or' => "Filter by :first, :second or :third",
        'filter_by' => 'Filter by'
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
        'name' => 'Enter name',
        'surname' => 'Enter surname',
        'father' => 'Enter father\'s name',
        'mail_coop' => 'Enter cooperative email',
        'mail' => 'Enter email',
        'password' => 'Enter password',
        'password_confirm' => 'Enter password confirmation',
        'or' => "Enter :first, :second or :third",
        'task_name' => 'Task Name',
        'search_users' => 'Search Users',
        'choose_file' => 'Choose file',
    ],

    'fields' => [
        'default_lang' => 'Default language',
        'created_at'=>'Created At',
        'personal' => 'PERSONAL',
        'employment' => 'EMPLOYMENT',
        'passport' => 'PASSPORT',
        'contact' => 'CONTACT',
        'address_title' => 'ADDRESS',
        'user' => 'User',
        'department' => 'Department',
        'intercity_phone' => 'Intercity Phone',
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
        'phone1' => 'Phone one',
        'phone2' => 'Phone two',
        'email1' => 'Email one',
        'email2' => 'Email two',
        'address1' => 'Address one',
        'address2' => 'Address two',
        'email_coop' => 'Cooperative Email',
        'email_private' => 'Personal Email',
        'address_coop' => 'Cooperative address',
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
        'enter' => 'Enter :field',
        'mail' => 'Email',
        'call_center' => 'Call center',
        'keywords' => 'Keywords',
        'detail' => 'Detail',
        'about' => 'About',
        'file' => 'File',
        'count' => 'Total Count',
        'priority' => [
            'key' => 'Priority',
            'options' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'urgent' => 'Urgent'
            ]
        ],
        'status' => [
            'key' => 'Status',
            'options' => [
                'to_do' => 'To do',
                'in_progress' => 'In progress',
                'done' => 'Done'
            ]
        ]
    ],

    'buttons' => [
        'create' => 'Create',
        'save' => 'Save',
        'back' => 'Back',
        'search' => 'Search',
        'filter' => 'Filter',
        'add' => 'Add',
        'copy' => 'Copy',
        'copied' => 'Copied',
        'previous' => 'Previous',
        'next' => 'Next',
        'change' => 'Change',
        'upload_file' => 'Upload file',
        'close' => 'Close',
        'view' => 'View',
        'verify' => 'Verify',
        'execute' => 'Execute'
    ],

    'sum' => 'Collective',
    'or' => 'or',

    'navbar' => [
        'general' => 'General',
        'dashboard' => 'Dashboard',
        'welcome' => 'Welcome',
        'cabinet' => 'Cabinet',
        'company' => 'Companies',
        'customer_company' => 'Customer Company',
        'account' => 'Account',
        'signature' => 'Signature',
        'inquiry' => 'Inquiries',
        'task' => 'Tasks',
        'parameter' => 'Parameters',
        'option' => 'Options',
        'role' => 'Roles',
        'user' => 'Users',
        'department' => 'Departments',
        'position' => 'Positions',
        'notification' => 'Notifications',
        'client' => 'Clients',
        'referral' => 'Referrals',
        'bonus' => 'Bonuses',
        'update' => 'Updates',
        'services' => 'Services',
        'work' => 'Works',
        'meeting' => 'Meetings',
        'conference' => 'Conferences',
        'document' => 'Documents',
        'service' => 'Service',
        'asan_imza'=>'Asan Signature',
        'customer_engagement'=>'Customer Engagement',
        'report' => 'Reports',
        'calendar' => 'Calendar',
        'announcement' => 'Announcements'
    ],

    'date' => [
        'today' => 'Today',
        'month' => 'This month'
    ],

    'register' => [
        'register' => 'Register',
        'fill' => 'Fill all form fields to go to next step',
        'progress' => [
            'language' => 'Language',
            'account' => 'Account',
            'personal' => 'Personal',
            'avatar' => 'Avatar'
        ],
        'steps' => 'Step :step - 4',
        'title' => 'Register as :type',
        'name' => 'Name',
        'surname' => 'Surname',
        'mail_coop' => 'Cooperative Email',
        'mail' => 'Email',
        'phone' => 'Personal phone',
        'department' => 'Department',
        'company' => 'Company',
        'password' => 'Password',
        'password_confirm' => 'Password confirmation',
    ],

    'login' => [
        'login' => 'Sign into your account',
        'remember' => 'Remember me',
        'forgot_pwd' => 'Forgot Your Password?',
        'no_account' => "Don't have an account?",
        'register_here' => 'Register here'
    ],

    'flash_messages' => [
        'inquiry_status_updated' => [
            'title' => ':code Updated',
            'msg' => 'Status updated from :prev to :next'
        ],
        'task_status_updated' => [
            'confirm' => ['title' => 'Update', 'msg' => 'Are you sure to change status from :prev to :next?'],
            'title' => ':name Updated',
            'msg' => 'Status updated from :prev to :next'
        ],
        'task_user_updated' => [
            'confirm' => ['title' => 'Update', 'msg' => 'Are you sure to change user from :prev to :next?'],
            'title' => ':name Updated',
            'msg' => 'User updated from :prev to :next'
        ]
    ],

    'loading' => 'Loading',

    'total_items' => 'Showing :count of :total',

    'total' => 'Total',

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
        'reply' => 'Reply',
        'send' => 'Send',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'end' => "That's all",
        'no_comments' => 'No comments available for now',
        'content' => [
            'user' => 'New task assigned to you',
            'department' => 'New task assigned to your department'
        ],
        'list' => [
            'to_do' => 'To do',
            'placeholder' => 'What should be done ?',
            'new' => 'You have new list in task you are assigned to',
            'checked' => ':name is done by :user',
        ],
        'types' => [
            'assigned_to_me' => 'Assigned to me',
            'my_tasks' => 'My tasks',
            'all' => 'All'
        ],
        'label' => [
            'name' => 'Task Name'
        ],
    ],

    'works' => [
        'new' => 'You have new work assigned',
        'content' => [
            'user' => 'New work assigned to you',
            'department' => 'New work assigned to your department'
        ],
        'statuses' => [
            'rejected' => 'The work you performed was not approved'
        ]
    ],

    'referrals' => [
        'link' => 'Your Referral link',
        'sub_message' => 'This is your referral link, copy and share with your friends',
        'total' => 'Total Referrals',
        'packages' => 'Packages',
        'earnings' => 'Earnings',
        'bonus' => 'Bonus balance',
        'get_data' => 'For getting referral data',
        'send_req' => 'Send request',
        'updated' => 'Last updated',
        'retry_later' => 'check again :count.'
    ],
    'updates' => [
        1 => 'Rejected',
        'Pending',
        'Accepted',
        'Started',
        'Done',
        'Upcoming',
        'Error',
        'Bug',
        'Fixed'
    ],

    'meetings' => [
        'deger1',
        'deger2',
        'deger3',

    ],

    'conferences' => [
        'deger1',
        'deger2',
        'deger3',
    ],

    'columns' => [
        'name' => 'Name',
        'priority' => 'Priority',
        'status' => 'Status',
        'created_by' => 'Created By',
        'created_at' => 'Created At',
        'user' => 'User',
        'stage' => 'Stage',
        'full_name' => 'Full Name',
        'fin' => 'FIN',
        'email' => 'Email',
        'phone' => 'Phone',
        'company' => 'Company',
        'department' => 'Department',
        'short_name' => 'Short Name',
        'role' => 'Role',
        'actions' => 'Actions',
        'adress' => 'Adress',
        'permissions' => 'Permissions',
        'order' => 'Order',
        'type' => 'Type',
        'parent_option' => 'Parent option',
        'deadline' => 'Deadline',
        'parameter_label' => 'Parameter Label',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
        'unverified' => 'Unverified',
        'price_verified' => 'Price Verified',
        'price_unverified' => 'Price Unverified',
        'reports_by_the_week' => 'Reports by the week'
    ],

    'notify' => [
        'successfully' => 'Successfully',
        'processed_successfully' => 'processed successfully',
        'sww' => 'Something went wrong',
        'record' => 'Record',
    ],

    'users' => [
        'titles' => [
            'partner' => "Partner",
            'employee' => "Employee",
        ],
        'types' => [
            'employees' => 'Employees',
            'partners' => 'Partners',
            'all' => 'All'
        ],
    ],

    'bonus' => [
        'effective' => 'Effective',
        'ineffective' => 'Ineffective'
    ],

    'general' => [
        'inquirable' => 'Is Inquirable',
        'sosials' => 'SOSIALS',
        'sosial' => 'Sosial',
        'no_sosial_link' => 'No Social links',
        'no_service_parameter' => 'No Service Parameter',
        'sosial_url' => 'Sosial Url',
        'empty' => 'Empty for now.',
        'not_found' => 'Not Found!',
        'get_signature' => 'Get My Signature',
        'signature_for' => 'Signature for',
        'legal' => 'Legal',
        'physical' => 'Physical',
        'physical_client_name' => 'Name of Legal Client Employees',
        'physical_client_mail' => 'Mail of Legal Client Employees',
        'physical_client_phone' => 'Phone of Legal Client Employees',
        'physical_client_position' => 'Position of Legal Client Employees',
        'done_at' => 'Done at',
        'verified_at' => 'Verified at',
        'started_at' => 'Started at',
        'earning' => 'Amount',
        'currency' => 'Currency',
        'currency_rate' => 'Currency Rate (AZN)',
        'select_service' => 'Select a Service',
        'work_service' => 'Service',
        'work_service_type' => 'Service type',
        'rate' => 'Rate(in AZN)',
        'hard_level_choose' => 'Hard Level Choose',
        'status_choose' => 'Status Choose',
        'hard_level' => 'Hard Level',
        'work_earning' => 'Amount',
        'work_detail' => 'Detail',
        'department_select' => 'Department Select',
        'user_select' => 'User Select',
        'select_client' => 'Select Client',
    ],

    'clients' => [
        'detail_empty' => 'Detail is empty',
        'phone_empty' => 'Phone is empty',
        'email_empty' => 'Email is empty',
        'voen_empty' => 'VOEN is empty',
        'add_representative' => 'Add a representative'
    ],

    'clients_type' => [
        'Legal',
        'Physical',
    ],

    'work_status' => [
        1 => 'Pending',
        'Continue',
        'Done',
        'Rejected'
    ],

    'hard_level' => [
        1 => 'Easy',
        'Medium',
        'Hard',
    ],

    'files' => [
        'default_title' => 'Related Files',
        'contract' => 'Contract'
    ],

    'calendar' => [
        'title' => 'Event',
        'eventTypes' => 'Event types',
        'fields' => [
            'select_type' => 'Select event type',
            'name' => 'Event name',
            'is_day_off' => 'Is Day Off',
            'is_repeatable' => 'Is Repeatable',
        ],
        'types' => [
            1 => 'Working day',
            'Holiday',
            'Day off',
            'Birthday',
            'Other'
        ]
    ],

    'reports' => [
        'check_new_chiefs' => 'Check for new chiefs'
    ]
];
