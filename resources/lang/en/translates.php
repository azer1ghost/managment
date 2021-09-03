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
        'clear' => 'Clear',
        'select' => 'Not Selected'
    ],

    'placeholders' => [
        'range' => 'Enter date range',
        'code' => 'Enter code',
        'note' => 'Enter note',
        'fullname' => 'Enter fullname',
        'phone' => 'Enter phone',
        'choose' => 'Choose',
        'name'   => 'Enter name',
        'surname'   => 'Enter surname',
        'mail_coop'   => 'Enter cooperative email',
        'mail'   => 'Enter email',
        'password' => 'Enter password',
        'password_confirm' => 'Enter password confirmation',
    ],

    'fields' => [
        'mgCode' => 'MG Code',
        'date' => 'Date',
        'time' => 'Time',
        'company' => 'Company',
        'clientName' => 'Client Name',
        'writtenBy' => 'Written By',
        'subject' => 'Subject',
        'status' => 'Status',
        'actions' => 'Actions',
        'contactMethod' => 'Contact method',
        'phone' => 'Phone',
        'note' => 'Note',
        'fullname' => 'Fullname',
        'client' => 'Client',
        'logo' => 'Logo',
        'name' => 'Name',
        'address' => 'Address',
        'website' => 'Website',
        'mobile' => 'Mobile',
        'mail' => 'Email',
        'call_center' => 'Call center',
        'keywords' => 'Keywords',
        'about' => 'About'
    ],

    'buttons' => [
        'create' => 'Create',
        'save'   => 'Save',
        'back'   => 'Back',
        'search' => 'Search',
        'filter' => 'Filter'
    ],

    'navbar' => [
        'general'    => 'General',
        'dashboard'  => 'Dashboard',
        'welcome'    => 'Welcome',
        'cabinet'    => 'Cabinet',
        'company'    => 'Companies',
        'account'    => 'Account',
        'signature'  => 'Signature',
        'inquiry'    => 'Inquiries',
        'parameter'  => 'Parameters',
        'option'     => 'Options',
        'role'       => 'Roles',
        'user'       => 'Users',
        'department' => 'Departments',
    ],

    'date' => [
        'today' => 'Today',
    ],

    'register' => [
        'register'   => 'Register',
        'title'      => 'Register as employer',
        'name'       => 'Name',
        'surname'    => 'Surname',
        'mail_coop'  => 'Cooperative Email',
        'mail'       => 'Email',
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
        ]
    ],

    'loading' => 'Loading',

    'total_items' => 'Showing :count of :total'

];
