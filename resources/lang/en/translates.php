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
        'filter_by' => 'Filter by',
        'free_clients' => 'Free clients',
        'free_company' => 'Free Company',
        'type' => 'Type',
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
        'comment' => 'Şərh yazın',
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
        'inappropriate_worker' => 'Inappropriate Worker',
        'position' => 'Position',
        'mgCode' => 'MG Code',
        'date' => 'Date',
        'paid_at' => 'Paid Date',
        'vat_paid_at' => 'VAT Paid Date',
        'invoiced_date' => 'Invoice Date',
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
        'cooperative_numbers' => 'Cooperative numbers',
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
        'bcreated_at' => 'Birthday or Created At',
        'work_started_at' => 'Date of employment',
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
                'Low',
                'Medium',
                'High',
                'Urgent'
            ]
        ],
        'status' => [
            'key' => 'Status',
            'options' => [
                'to_do' => 'To do',
                'in_progress' => 'In progress',
                'done' => 'Done',
                'redirected' => 'Redirected',
            ]
        ]
    ],

    'buttons' => [
        'create' => 'Create',
        'save' => 'Save',
        'back' => 'Back',
        'search' => 'Search',
        'filter' => 'Filter',
        'filter_open' => 'Open Filter',
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
        'export' => 'Export',
        'execute' => 'Execute',
        'show' => 'Show',
        'send_sms' => 'Send Sms',
        'send' => 'Göndər',
    ],

    'sum' => 'Collective',
    'or' => 'or',

    'navbar' => [
        'general' => 'General',
        'dashboard' => 'Dashboard',
        'welcome' => 'Welcome',
        'cabinet' => 'Cabinet',
        'company' => 'Companies',
        'changes' => 'Changes',
        'registration_logs' => 'Registration Logs',
        'customer_company' => 'Customer Company',
        'account' => 'Account',
        'signature' => 'Signature',
        'inquiry' => 'Inquiries',
        'inquiry_sales' => 'Inquiry Sales',
        'barcode' => 'Barcodes',
        'task' => 'Tasks',
        'parameter' => 'Parameters',
        'necessary' => 'Necessary documents',
        'option' => 'Options',
        'role' => 'Roles',
        'user' => 'Users',
        'department' => 'Departments',
        'position' => 'Positions',
        'notification' => 'Notifications',
        'client' => 'Clients',
        'referral' => 'Referrals',
        'reference' => 'Referans',
        'intermediary' => 'İntermediary',
        'bonus' => 'Bonuses',
        'update' => 'Updates',
        'services' => 'Services',
        'work' => 'Works',
        'plannedWorks' => 'Planned Works',
        'pendingWorks' => 'Pending Works',
        'financeWorks' => 'Finance',
        'meeting' => 'Meetings',
        'conference' => 'Conferences',
        'document' => 'Documents',
        'service' => 'Service',
        'asan_imza'=>'Asan Signature',
        'customer_engagement'=>'Customer Engagement',
        'report' => 'Reports',
        'calendar' => 'Calendar',
        'certificate' => 'Certificates',
        'organization' => 'Organizations',
        'announcement' => 'Announcements',
        'sales_activities_type' => 'Types Of Activities',
        'sales_activities' => 'Sales Activities',
        'sales_client' => 'Sales Client',
        'partners' => 'Partners',
        'sales' => 'Sales',
        'human_resources' => 'Human Resources',
        'law' => 'Law',
        'structure' => 'Structure',
        'intern_number' => 'CISCO Numbers',
        'job_instruction' => 'Job Instructions',
        'intern_relation' => 'Intern Relations',
        'internal_document' => 'Internal Document',
        'foreign_relation' => 'Foreign Relations',
        'instruction' => 'Video Instruction',
        'employee_satisfaction' => 'Employee Satisfaction',
        'satisfaction' => 'Satisfaction',
        'customer-satisfaction' => 'Customer Satisfaction'
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
        'alarm' => 'You must contact the customer'
    ],

    'tasks' => [
        'is_executing' => 'Is executing',
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
        'creator' => 'Creator',
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
        'paid' => 'Paid',
        'vat_paid' => 'VAT Paid',
        'expired' => 'Expired',
        'total' => 'Total',
        'user_works' => 'User\'s Works',
        'rejected' => 'Rejected',
        'unverified' => 'Unverified',
        'price_verified' => 'Price Verified',
        'price_unverified' => 'Price Unverified',
        'reports_by_the_week' => 'Reports by the week',
        'detail' => 'Detail',
        'attribute' => 'Attribute',
        'internal_number' => 'Internal Number',
        'organization' => 'Qurum',
        'is_certificate' => 'Sertifikat',
        'activity_area' => 'Activity Area',
        'sales_activity' => 'Sales Activity',
        'description' => 'Description',
        'hard_columns' => 'Hard Columns',
        'evaluation' => 'Evaluation',
        'sales_client' => 'Sales Clients',
        'partner' => 'Partner',
        'will_notify_at' => 'Will Notify At',
        'will_end_at' => 'Will End At',
        'will_start_at' => 'Will Start At',
        'repeat_rate' => 'Repeat Rate',
        'class' => 'Category',
        'title' => 'Title',
        'date_time' => 'Date Time',
        'residue' => 'Balance',
        'sum_paid' => 'Sum Payment',
        'executant' => 'Executant',
        'url' => 'Url',
        'rate' => 'Rate',
        'price_rate' => 'Price',
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
        'statuses' => [
            'active' => 'Active',
            'deactivate' => 'Deactivate',
            'all' => 'Hamısı'
        ],
    ],

    'bonus' => [
        'effective' => 'Effective',
        'ineffective' => 'Ineffective'
    ],

    'effectivity' => [
        1 => 'Effective',
        2 => 'Ineffective'
    ],

    'employee_satisfactions' => [
        'satisfaction_types' => 'Types of Satisfaction',
        'is_enough' => 'Is the measure taken enough?',
        'reason' => 'Reason for failure of corrective action',
        'result' => 'Result of corrective action',
        'effectivity' => 'Efficiency',
        'more_time' => 'Need more time?',
        'activity' => 'Corrective Action',
        'content-1' => 'Offer and Request',
        'content-2' => 'Your complaint',
        'content-3' => 'The content of the discrepancy',
        'types' => [
            '1' => 'Offer',
            '2' => 'Complaint',
            '3' => 'Incompatibility'
        ],
        'statuses' => [
            '1' => 'Not Viewed',
            '2' => 'Evaluating',
            '3' => 'appreciated',
            '4' => 'Executed',
            '5' => 'Rejected',
        ]
    ],

    'general' => [
        'inquirable' => 'Is Inquirable',
        'sosials' => 'SOSIALS',
        'sosial' => 'Sosial',
        'no_sosial_link' => 'No Social links',
        'no_users' => 'No users',
        'common' => 'Common',
        'no_service_parameter' => 'No Service Parameter',
        'sosial_url' => 'Sosial Url',
        'empty' => 'Empty for now.',
        'not_found' => 'Not Found!',
        'get_signature' => 'Get My Signature',
        'signature_for' => 'Signature for',
        'legal' => 'Legal',
        'physical' => 'Physical',
        'typeChoose' => 'Type Not Selected',
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
        'partner_select' => 'Partner Select',
        'select_client' => 'Select Client',
        'select_date' => 'Select Date',
        'all' => 'All',
        'client_data' => 'Client Information',
        'currency_value' => 'Currency Value',
        'symbol' => 'Symbol',
        'satisfaction' => 'Satisfaction',
        'payment_method' => 'Payment Method',
        'no_announcement' => 'No announcement yet',
        'no_message' => 'No message yet',
        'mark_as_read' => 'Mark as Read',
        'mark_all' => 'Mark All as Read',
        'declaration' => 'Declaration',
        'all_departments' => 'All Departments',
        'accepted' => 'Accepted',
    ],

    'clients' => [
        'detail_empty' => 'Detail is empty',
        'phone_empty' => 'Phone is empty',
        'email_empty' => 'Email is empty',
        'voen_empty' => 'VOEN is empty',
        'add_representative' => 'Add a representative',
        'selectUser' => 'Select Sales Users',
        'assignUser' => 'Assign sales users',
        'assignCompany' => 'Assign Companies',
        'selectCompany' => 'Select Company'

    ],

    'clients_type' => [
        'Legal',
        'Physical',
    ],

    'work_status' => [
        1 => 'Planned',
        'Pending',
        'Continue',
        'Injected to System',
        'Returned',
        'Done',
        'Rejected',
    ],

    'payment_methods' => [
         1 => 'Cash',
         'Bank',
         'PBank'
    ],

    'satisfactions' => [
        1 => 'Satisfied',
        'Unsatisfied',
        'Unknown'
    ],

    'hard_level' => [
        1 => 'Easy',
        'Medium',
        'Hard',
    ],

    'files' => [
        'default_title' => 'Related Files',
        'contract' => 'Contract',
        'personal_work' => 'Personal Work'

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

    'customer_satisfaction' => [
        'rate' => 'Xidmət səviyyəsini qiymətləndirin',
        'price_rate' => 'Xidmət haqqı sizi qane etti mi?',
        'rates' => [
            1 => 'Çox Pis',
            2 => 'Pis',
            3 => 'Orta',
            4 => 'Yaxşı',
            5 => 'Əla',
        ],
        'content' => 'Xidmət səviyyəmizi artırmaq üçün fikirlərinizi bilmək bizim üçün önəmlidir.',
        'note' => 'Təklif, İrad və Şikayətləriniz'
    ],

    'reports' => [
        'check_new_chiefs' => 'Check for new chiefs'
    ],
    'errors' => [
        '404' => 'Page Not Found',
        '403' => 'You don\'t have permission to view this resource',
        '503' => 'Currently, the system is undergoing preventive work. Thanks for your understanding'
    ],
    'sales_supply' => [
        'sales_supply' => 'Sales Supply',
        'supply_name' => 'Supply Name',
        'supply_value' => 'Supply Value',
    ],
    'widgets' => [
        'number_of_users' => 'Number Of Users',
        'number_of_inquiries' => 'Number Of Inquiries',
        'number_of_works' => 'Number Of Works',
        'number_of_tasks' => 'Number Of Tasks',
        'welcome_msg' => 'We wish you a happy day',
        'you_have' => 'You Have :count tasks'
    ],
    'chats' => [
        'title' => 'The message has been sent to you',
    ]

];
