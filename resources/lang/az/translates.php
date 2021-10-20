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
        'serial_pattern' => 'Seriya nömrəsini daxil edin',
        'fin' => 'FİN daxil edin',
        'range' => 'Tarix aralığı daxil edin',
        'code' => 'Kodu daxil edin',
        'note' => 'Qeyd daxil edin',
        'fullname' => 'Tam adı daxil edin',
        'phone' => 'Nömrənizi daxil edin',
        'phone_coop' => 'Kooperativ nömrənizi daxil edin',
        'address' => 'Ünvan daxil edin',
        'choose' => 'Seç',
        'name'   => 'Adınızı daxil edin',
        'surname'   => 'Soyadınızı daxil edin',
        'father'   => 'Atan adını daxil edin',
        'mail_coop'   => 'Kooperativ e-poçtunuzu daxil edin',
        'mail'   => 'E-poçtunuzu daxil edin',
        'password' => 'Şifrə daxil edin',
        'password_confirm' => 'Şifrə təsdiqini daxil edin',
        'or' => ":first, :second və ya :third daxil edin",
    ],

    'fields' => [
        'default_lang' => 'Varsayılan dil',
        'personal' => 'ŞƏXSİ',
        'employment' => 'MƏŞĞULLUQ',
        'passport' => 'PASPORT',
        'contact' => 'ƏLAQƏ',
        'address_title' => 'ÜNVAN',
        'user' => 'Əməkdaş',
        'department' => 'Şöbə',
        'position' => 'Vəzifə',
        'mgCode' => 'MG Kodu',
        'date' => 'Tarix',
        'time' => 'Vaxt',
        'company' => 'Şirkət',
        'clientName' => 'Müştəri Adı',
        'writtenBy' => 'Müəllif',
        'subject' => 'Mövzu',
        'actions' => 'Əməliyyatlar',
        'contactMethod' => 'Əlaqə üsulu',
        'phone' => 'Nömrə',
        'phone_private' => 'Şəxsi nömrə',
        'phone_coop' => 'Kooperativ nömrə',
        'email_coop' => 'Kooperativ e-poçt',
        'email_private' => 'Şəxsi e-poçt',
        'country' => 'Ölkə',
        'city' => 'Şəhər',
        'password' => 'Şifrə',
        'password_confirm' => 'Şifrə təsdiqi',
        'role' => 'Rol',
        'note' => 'Qeyd',
        'fullname' => 'Tam adı',
        'client' => 'Müştəri',
        'logo' => 'Loqo',
        'name' => 'Ad',
        'surname' => 'Soyad',
        'father' => 'Ata Adı',
        'serial' => 'Seriya',
        'gender' => 'Cins',
        'birthday' => 'Doğum tarixi',
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
        'add'    => 'Əlavə et',
        'copy'   => 'Kopyala',
        'copied' => 'Kopyalandı',
        'previous' => 'Əvvəlki',
        'next' => 'Sonrakı',
        'change' => 'Dəyişdirin',
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
        'task'       => 'Tapşırıqlar',
        'parameter'  => 'Parametrlər',
        'option'     => 'Seçimlər',
        'role'       => 'Rollar',
        'user'       => 'İstifadəçilər',
        'department' => 'Şöbələr',
        'position'   => 'Vəzifələr',
        'notification' => 'Bildirişlər',
        'client'     => 'Müştərilər',
        'referral'  => 'Referrallar',
        'bonus'     => 'Bonuslar'
    ],

    'date' => [
        'today' => 'Bu gün',
        'month' => 'Bu ay',
    ],

    'register' => [
        'register'  => 'Qeydiyyat',
        'fill' => 'Növbəti mərhələyə keçmək üçün bütün forma sahələrini doldurun',
        'progress' => [
            'language' => 'Dil',
            'account' => 'Hesab',
            'personal' => 'Şəxsi',
            'avatar' => 'Şəkil'
        ],
        'steps' => 'Addım :step - 4',
        'title'     => 'Əməkdaş kimi qeydiyyatdan keçin',
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
        ],
        'task_status_updated' => [
            'confirm' => ['title' => 'Yenilə', 'msg' => 'Statusu :prev dən :next ə dəyişəcəyinizdən əminsiniz?'],
            'title' => ':name Yeniləndi',
            'msg'   => ':prev, :next statusuna dəyiştirildi'
        ]
    ],

    'loading' => 'Yüklənir',

    'total_items' => 'Göstərilən: :count.  Ümumi: :total',

    'access' => 'Icazə',

    'disabled' => 'Aktiv deyil',

    'logout' => 'Çıxış',

    'countries' => [
        'Azerbaijan' => 'Azərbaycan',
        'Turkey' => 'Türkiyə'
    ],

    'cities' => [
        'Baku' => 'Bakı',
        'Sumgayit' => 'Sumqayıt'
    ],

    'gender' => [
        'male' => 'Kişi',
        'female' => 'Qadın',
    ],

    'comments' => [
        'new' => 'Yeni rəyiniz var'
    ],

    'inquiries' => [
        'types' => [
            'from_us' => 'Bizdən',
            'from_customers' => 'Müştərilərdən'
        ],
        'label' => 'Sorğu növü',
    ],

    'tasks' => [
        'created' => "Yeni tapşırığ :name uğurla əlavə edildi. <p> Zəhmət olmasa, Ediləcəkləri təyin edin. </p>",
        'not_started' => 'Başlamayıb',
        'new' => 'Sizə yeni bir tapşırıq təyin edildi',
        'content' => [
            'user' => 'Sizə yeni tapşırıq verildi',
            'department' => 'Şöbənizə yeni bir tapşırıq verildi'
        ],
        'list' => [
            'to_do' => 'Ediləcəklər',
            'placeholder' => 'Nə edilməlidir ?',
            'new' => 'Sizə təyin edilmiş tapşırığa yeni bir iş əlavə edildi'
        ],
        'types' => [
            'assigned_to_me' => 'Mənə tapşırılıb',
            'my_tasks' => 'Mənim tapşırdıqlarım',
            'all' => 'Hamısı'
        ]
    ],

    'referrals' => [
        'link' => 'Sizin referal linkiniz',
        'sub_message' => 'Bu referal linkinizdir, kopyalayın və dostlarınızla paylaşın',
        'total' => 'Toplam referallar',
        'packages' => 'Bağlamalar',
        'earnings'  => 'Qazanc',
        'bonus'   => 'Bonus balansı',
        'get_data' => 'Referal məlumatlarını əldə etmək üçün',
        'send_req' => 'Sorğu göndərin',
        'updated' => 'Son yenilənmə',
        'retry_later' => ':count dəqiqədən sonra yenidən yoxlayın.'
    ]

];
