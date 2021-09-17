<?php

return [

    'parameters' => [
        'types' => [
            'contact_method' => 'Əlaqə vasitəsi',
            'subject' => 'Mövzü',
            'source' => 'Mənbə',
            'kind' => 'Növü',
            'operation' => 'Əməliyyat',
            'status' => 'Status',
        ],
    ],

    'filters' => [
        'date' => 'Tarixə görə filtrləyin',
        'code' => 'Kod üzrə filtrləyin',
        'subject' => 'Mövzuya görə filtrləyin',
        'company' => 'Şirkətə görə filtrləyin',
        'client_name' => 'Müştəri adına görə filtrləyin',
        'phone' => 'Telefon adına görə filtrləyin',
        'written_by' => 'Müəllif adına görə filtrləyin',
        'email' => 'E-poçtaya görə filtrləyin',
        'source' => 'Mənbəyə görə filtrləyin',
        'contact_method' => 'Əlaqə vasitəsinə görə filtrləyin',
        'clear' => 'Sil',
        'select' => 'Seçilməyib',
        'or' => ":first, :second və ya :third filtrləyin",
    ],

    'placeholders' => [
        'range' => 'Tarix aralığı daxil edin',
        'code' => 'Kodu daxil edin',
        'note' => 'Qeyd daxil edin',
        'fullname' => 'Tam adı daxil edin',
        'phone' => 'Telefon daxil edin',
        'choose' => 'Seç',
        'name'   => 'Adınızı daxil edin',
        'surname'   => 'Soyadınızı daxil edin',
        'mail_coop'   => 'Kooperativ e-poçtunuzu daxil edin',
        'mail'   => 'E-poçtunuzu daxil edin',
        'password' => 'Şifrə daxil edin',
        'password_confirm' => 'Şifrə təsdiqini daxil edin',
        'or' => ":first, :second və ya :third daxil edin",
    ],

    'fields' => [
        'user' => 'Əməkdaş',
        'department' => 'Şöbə',
        'mgCode' => 'MG Kodu',
        'date' => 'Tarix',
        'time' => 'Vaxt',
        'company' => 'Şirkət',
        'clientName' => 'Müştəri Adı',
        'writtenBy' => 'Müəllif',
        'subject' => 'Mövzu',
        'actions' => 'Əməliyyatlar',
        'contactMethod' => 'Əlaqə üsulu',
        'phone' => 'Telefon',
        'note' => 'Qeyd',
        'fullname' => 'Tam adı',
        'client' => 'Müştəri',
        'logo' => 'Loqo',
        'name' => 'Ad',
        'address' => 'Ünvan',
        'website' => 'Sayt',
        'mobile' => 'Mobil',
        'mail' => 'E-poçt',
        'call_center' => 'Çağrı Mərkəzi',
        'keywords' => 'Açar sözlər',
        'about' => 'Haqqında',
        'priority' => [
            'key' => 'Prioritet',
            'options'  => [
                'low' => 'Aşağı',
                'medium' => 'Orta',
                'high' => 'Yüksək',
                'urgent' => 'Təcili'
            ]
        ],
        'status' => [
            'key' => 'Status',
            'options'  => [
                'to_do' => 'Ediləcək',
                'in_progress' => 'Davam edən',
                'done' => 'Bitmiş'
            ]
        ]
    ],

    'buttons' => [
        'create' => 'Əlavə et',
        'save'   => 'Yadda saxla',
        'back'   => 'Geri',
        'search' => 'Axtar',
        'filter' => 'Filterlə',
    ],

    'or' => 'və ya',

    'navbar' => [
        'general'    => 'Ümumi',
        'welcome'    => 'Xoş gəldiniz',
        'dashboard'  => 'Məlumat Paneli',
        'cabinet'    => 'Kabinet',
        'company'    => 'Şirkətlər',
        'account'    => 'Hesab',
        'signature'  => 'Email İmza',
        'inquiry'    => 'Sorğular',
        'task'       => 'İcra olunacaqlar',
        'parameter'  => 'Parametrlər',
        'option'     => 'Seçimlər',
        'role'       => 'Rollar',
        'user'       => 'İstifadəçilər',
        'department' => 'Şöbələr',
        'position'   => 'Vəzifələr',
        'notification' => 'Bildirişlər',
        'client'     => 'Müştərilər',
    ],

    'date' => [
        'today' => 'Bu gün',
        'month' => 'Bu ay',
    ],

    'register' => [
        'register'  => 'Qeydiyyat',
        'title'     => 'İşçi kimi qeydiyyatdan keçin',
        'name'      => 'Ad',
        'surname'   => 'Soyad',
        'mail_coop' => 'Kooperativ e-poçt',
        'mail' => 'E-poçt',
        'phone'       => 'Personal telefon',
        'department' => 'Şöbə',
        'company' => 'Şirkət',
        'password' => 'Şifrə',
        'password_confirm' => 'Şifrə təsdiqi',
    ],

    'login' => [
        'login' => 'Hesabınıza daxil olun',
        'remember' => 'Yadda saxla',
        'forgot_pwd' => 'Şifrəni unutmusan?',
        'no_account' => 'Hesabınız yoxdur?',
        'register_here' => 'Burada qeydiyyatdan keçin'
    ],

    'flash_messages' => [
        'inquiry_status_updated' => [
            'title' => ':code Yeniləndi',
            'msg'   =>':prev, :next statusuna dəyiştirildi'
        ]
    ],

    'loading' => 'Yüklənir',

    'total_items' => 'Göstərilən: :count.  Ümumi: :total',

    'access' => 'Icazə'

];
