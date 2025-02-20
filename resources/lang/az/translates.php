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
        'supplier' => 'Tədarükçüyə görə filtrləyin',
        'coordinator' => 'Koordinatora görə filtrləyin',
        'Sale' => 'Satışa görə filtrləyin',
        'client_name' => 'Müştəri adına görə filtrləyin',
        'phone' => 'Telefon adına görə filtrləyin',
        'written_by' => 'Müəllif adına görə filtrləyin',
        'email' => 'E-poçtaya görə filtrləyin',
        'source' => 'Mənbəyə görə filtrləyin',
        'contact_method' => 'Əlaqə vasitəsinə görə filtrləyin',
        'clear' => 'Filteri təmizlə',
        'select' => 'Seçilməyib',
        'or' => ":first, :second və ya :third filtrləyin",
        'filter_by' => 'Filterlə',
        'free_clients' => 'Boş müştərilər',
        'free_company' => 'Boş Şirkət',
        'free_coordinator' => 'Boş Koordinator',
        'free_sale' => 'Boş Satış işçisi',
        'sales_activities_type' => 'Satış Fəaliyyətinə Görə Filterləyin',
        'type' => 'Növü',
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
        'address_coop' => 'Kooperativ adresinizi daxil edin',
        'address' => 'Ünvan daxil edin',
        'choose' => 'Seç',
        'name' => 'Adınızı daxil edin',
        'surname' => 'Soyadınızı daxil edin',
        'father' => 'Ata adını daxil edin',
        'mail_coop' => 'Kooperativ e-poçtunuzu daxil edin',
        'mail' => 'E-poçtunuzu daxil edin',
        'password' => 'Şifrə daxil edin',
        'password_confirm' => 'Şifrə təsdiqini daxil edin',
        'or' => ":first, :second və ya :third daxil edin",
        'task_name' => 'Tapşırıq adı',
        'search_users' => 'İstifadəçi axtar',
        'choose_file' => 'Fayl seç',
        'comment' => 'Şərh yazın',
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
        'date' => 'Bitmə Tarixi',
        'created_at'=>'Yaranma Tarixi',
        'injected_at'=>'Sistemə Vurulma Tarixi',
        'entry_date'=>'Giriş Tarixi',
        'inappropriate_worker' => 'Uyğunsuzluq aşkarlanan işçi',
        'paid_at'=>'Ödənmə Tarixi',
        'vat_paid_at'=>'ƏDV Ödənmə Tarixi',
        'invoiced_date'=>'E-Qaimə Tarixi',
        'time' => 'Vaxt',
        'company' => 'Şirkət',
        'clientName' => 'Müştəri Adı',
        'intercity_phone' => 'Şəhərlərarası Telefon',
        'writtenBy' => 'Müəllif',
        'subject' => 'Mövzu',
        'actions' => 'Əməliyyatlar',
        'contactMethod' => 'Əlaqə üsulu',
        'phone' => 'Nömrə',
        'phone2' => 'Direktor nömrəsi',
        'phone3' => 'Mühasib nömrəsi',
        'email1' => 'E-poçt bir',
        'email2' => 'E-poçt iki',
        'address1' => 'Ünvan bir',
        'address2' => 'Ünvan iki',
        'phone_private' => 'Şəxsi nömrə',
        'phone_coop' => 'Kooperativ nömrə',
        'cooperative_numbers' => 'Korporativ nömrələr',
        'email_coop' => 'Kooperativ e-poçt',
        'address_coop' => 'Kooperativ adres',
        'email_private' => 'Şəxsi e-poçt',
        'country' => 'Ölkə',
        'city' => 'Şəhər',
        'sector' => 'Sektor',
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
        'coefficient' => 'GB kofsent',
        'qib_coefficient' => 'QİB kofsent',
        'gross' => 'Gross',
        'bonus' => 'Bonus',
        'birthday' => 'Doğum tarixi',
        'work_started_at' => 'İşə başlama tarixi',
        'bcreated_at' => 'Doğum günü yaxud Yaranma tarixi',
        'address' => 'Ünvan',
        'website' => 'Sayt',
        'mark' => 'Qeydiyyat Nişanı',
        'mobile' => 'Mobil',
        'enter' => ':field daxil edin',
        'detail' => 'Detal',
        'mail' => 'E-poçt',
        'call_center' => 'Çağrı Mərkəzi',
        'keywords' => 'Açar sözlər',
        'about' => 'Haqqında',
        'file' => 'Fayl',
        'count' => 'Ümumi Sayı',
        'priority' => [
            'key' => 'Prioritet',
            'options' => [
                'Aşağı',
                'Orta',
                'Yüksək',
                'Təcili'
            ]
        ],
        'status' => [
            'key' => 'Status',
            'options' => [
                'to_do' => 'Ediləcək',
                'in_progress' => 'İcra olunur',
                'done' => 'Bitmiş',
                'redirected' => 'Yönləndirilib',
            ]
        ]
    ],

    'buttons' => [
        'create' => 'Əlavə et',
        'save' => 'Yadda saxla',
        'back' => 'Geri',
        'search' => 'Axtar',
        'filter' => 'Filterlə',
        'filter_open' => 'Filteri Aç',
        'add' => 'Əlavə et',
        'copy' => 'Kopyala',
        'copied' => 'Kopyalandı',
        'previous' => 'Əvvəlki',
        'next' => 'Sonrakı',
        'change' => 'Dəyişdirin',
        'upload_file' => 'Fayl yüklə',
        'close' => 'Bağla',
        'view' => 'Göstər',
        'verify' => 'Doğrulayın',
        'execute' => 'Icra et',
        'export' => 'İxrac et',
        'show' => 'Göstər',
        'send_sms' => 'SMS Göndər',
        'is_service' => 'Xidmət Qiymətləndirməsi',
        'send_email' => 'Email Göndər',
        'send' => 'Göndər',
        'active' => 'Müqaviləsi Aktivdir.',
        'passive' => 'Müqaviləsi Deaktivdir.',
    ],

    'sum' => 'Toplu',
    'or' => 'və ya',

    'navbar' => [
        'general' => 'Ümumi',
        'welcome' => 'Xoş gəldiniz',
        'dashboard' => 'Məlumat Paneli',
        'cabinet' => 'Kabinet',
        'company' => 'Şirkətlər',
        'changes' => 'Dəyişikliklər',
        'commands' => 'Əmrlər',
        'supports' => 'Dəstəkçilər',
        'summits' => 'Görüşlər',
        'registration_logs' => 'Qeydiyyat Jurnalı',
        'customer_company' => 'Müştəri Şirkətlər',
        'account' => 'Hesab',
        'signature' => 'Email İmza',
        'inquiry' => 'Sorğular',
        'inquiry_sales' => 'Zənglər',
        'barcode' => 'Barkodlar',
        'task' => 'Tapşırıqlar',
        'total' => 'Toplam',
        'necessary' => 'Lazımi Sənədlər',
        'parameter' => 'Parametrlər',
        'option' => 'Seçimlər',
        'role' => 'Rollar',
        'user' => 'İstifadəçilər',
        'department' => 'Şöbələr',
        'position' => 'Vəzifələr',
        'notification' => 'Bildirişlər',
        'client' => 'Müştərilər',
        'referral' => 'Referrallar',
        'reference' => 'Referans',
        'intermediary' => 'Vasitəçi',
        'bonus' => 'Bonuslar',
        'update' => 'Yeniləmələr',
        'services' => 'Xidmətlər',
        'work' => 'Ümumi İşlər',
        'plannedWorks' => 'Planlanan İşlər',
        'incompletedWorks' => 'Tamamlanmamış işlər',
        'pendingWorks' => 'Gözləmədəki İşlər',
        'financeWorks' => 'Ödənişlər',
        'meeting' => 'Görüşmələr',
        'conference' => 'İclaslar',
        'document' => 'Sənədlər',
        'service' => 'Xidmət',
        'asan_imza'=>'Asan Imza',
        'access_rate'=>'Giriş Dərəcəsi',
        'customer_engagement'=>'Müştəri Cəlbi',
        'report' => 'Hesabatlar',
        'calendar' => 'Təqvim',
        'certificate' => 'Sertifikatlar',
        'organization' => 'Qurumlar',
        'announcement' => 'Elanlar',
        'sales_activities_type' => 'Fəaliyyət Növləri',
        'sales_activities' => 'Satış Fəaliyyətləri',
        'sales_client' => 'Satış Müştəriləri',
        'partners' => 'Partnyorlar',
        'sales' => 'Satış',
        'human_resources' => 'İnsan Resursları',
        'law' => 'Hüquq',
        'structure' => 'Struktur',
        'intern_number' => 'CISCO Nömrələr',
        'job_instruction' => 'Vəzifə Təlimatları',
        'intern_relation' => 'Daxili Əlaqələr',
        'internal_document' => 'Daxili Sənədlər',
        'protocols' => 'Protokollar',
        'folder' => 'Qovluqlar',
        'sent_document' => 'Göndərilən Sənədlər',
        'iso_document' => 'Iso Sənədləri',
        'foreign_relation' => 'Xarici Əlaqələr',
        'instruction' => 'Video Təlimat',
        'employee_satisfaction' => 'İşçi Məmnuniyyəti',
        'satisfaction' => 'Məmnuniyyət',
        'customer-satisfaction' => 'Müştəri Məmnuniyyəti',
        'order' => 'Sifarişlər',
        'logistics' => 'Logistika',
        'logistics_clients' => 'Logistika Müştəriləri',
        'questionnaire' => 'Anket',
        'room' => 'Otaqlar',
        'presentation' => 'Prezentasiya',
        'supplier' => 'Tədarükçülər',
        'evaluation' => 'Qiymətləndirmə',
        'creditor' => 'Kreditorlar',
        'finance' => 'Maliyyə',
        'accounts' => 'Hesablar',
        'transaction' => 'Tranzaksiyalar',
        'fund' => 'Bank və Kodlar',
        'salary' => 'Əmək Haqqı',
        'rule' => 'Qayda',
        'rules' => 'Qaydalar',
    ],

    'questionnaire' => [
        'statuses' => [
            1 => 'Bakı Baş Gömrük İdarəsi Filialı',
            2 => 'Aksizli Mallar üzrə Baş Gömrük İdarəsi Filialı',
            3 => 'Hava Nəqliyyatında Baş Gömrük İdarəsi Filialı',
            4 => 'Sumqayıt Baş Gömrük İdarəsi Filialı',
            5 => 'Enerji Resursları və Dəniz Nəqliyyatında Baş Gömrük İdarəsi Filialı',
            6 => 'Qərb Ərazi Baş Gömrük İdarəsi Filialı',
            7 => 'Şimal Ərazi Baş Gömrük İdarəsi Filialı',
            8 => 'Cənub Ərazi Baş Gömrük İdarəsi Filialı',
        ],
        'sources' => [
            1 => 'Dostum/Qohumum deyib',
            2 => 'Satış meneceri zəng edib',
            3 => 'Tədbirlərdə Görmüşəm',
            4 => 'Website',
            5 => 'Google',
            6 => 'Instagram',
            7 => 'Facebook',
            8 => 'Linkedin',
            9 => 'Youtube',
            10 => 'E-mail',
        ],
        'novelty_us' => 'Bizdə nəyi görmək istəyirsiniz?',
        'novelty_customs' => 'Gömrükdə hansı yeniliyi görmək istəyərdiniz?',
        'what_source' => 'Bizim haqqımızda haradan məlumat əldə etmisiniz?',
        'what_customs' => 'İşləriniz ən çox hansı gömrük idarəsində olur?',
    ],

    'date' => [
        'today' => 'Bu gün',
        'month' => 'Bu ay',
    ],

    'register' => [
        'register' => 'Qeydiyyat',
        'fill' => 'Növbəti mərhələyə keçmək üçün bütün forma sahələrini doldurun',
        'progress' => [
            'language' => 'Dil',
            'account' => 'Hesab',
            'personal' => 'Şəxsi',
            'avatar' => 'Şəkil'
        ],
        'steps' => 'Addım :step - 4',
        'title' => ':type kimi qeydiyyatdan keçin',
        'name' => 'Ad',
        'surname' => 'Soyad',
        'mail_coop' => 'Kooperativ e-poçt',
        'mail' => 'E-poçt',
        'phone' => 'Personal telefon',
        'department' => 'Şöbə',
        'company' => 'Şirkət',
        'password' => 'Şifrə',
        'password_confirm' => 'Şifrə təsdiqi',
    ],

    'login' => [
        'login' => 'Hesabınıza daxil olun',
        'remember' => 'Yadda saxla',
        'forgot_pwd' => 'Şifrəmi Bərpa et',
        'no_account' => 'Hesabınız yoxdur?',
        'register_here' => 'Burada qeydiyyatdan keçin'
    ],

    'flash_messages' => [
        'inquiry_status_updated' => [
            'title' => ':code Yeniləndi',
            'msg' => ':prev, :next statusuna dəyiştirildi'
        ],
        'task_status_updated' => [
            'confirm' => ['title' => 'Yenilə', 'msg' => 'Statusu :prev dən :next ə dəyişəcəyinizdən əminsiniz?'],
            'title' => ':name Yeniləndi',
            'msg' => ':prev, :next statusuna dəyiştirildi'
        ],
        'task_user_updated' => [
            'confirm' => ['title' => 'Yenilə', 'msg' => 'Əməkdaşı :prev dən :next ə dəyişəcəyinizdən əminsiniz?'],
            'title' => ':name Yeniləndi',
            'msg' => ':prev, :next əməkdaşına dəyiştirildi'
        ]
    ],

    'loading' => 'Yüklənir',

    'total_items' => 'Göstərilən: :count.  Ümumi: :total',

    'total' => 'Toplam',
    
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
        'types' =>[
            1 => 'Potensial Müştəri',
            2 => 'Əməkdaşlıq Təklifi',
            3 => 'Vendor',
            4 => 'Partnyor',
            5 => 'Vakansiya',
        ],
        'priorities' => [
            0 => 'Yoxdur',
            1 => 'Aşağı',
            2 => 'Orta',
            3 => 'Yüksək',
        ],
        'where' => [
            'from_us' => 'Bizdən',
            'from_customers' => 'Müştərilərdən'
        ],
        'label' => 'Sorğu növü',
        'alarm' => 'Müştəri ilə əlaqə saxlamalısınız'
    ],


    'tasks' => [
        'is_executing' => 'İcra olunur',
        'created' => "Yeni tapşırığ :name uğurla əlavə edildi. <p> Zəhmət olmasa, Ediləcəkləri təyin edin. </p>",
        'not_started' => 'Başlamayıb',
        'new' => 'Sizə yeni bir tapşırıq təyin edildi',
        'reply' => 'Cavab ver',
        'send' => 'Göndər',
        'edit' => 'Düzəltmək',
        'delete' => 'Silmək',
        'end' => 'Bu qədər',
        'no_comments' => 'Hal hazırda rəy yoxdur',
        'content' => [
            'user' => 'Sizə yeni tapşırıq verildi',
            'department' => 'Şöbənizə yeni bir tapşırıq verildi'
        ],
        'list' => [
            'to_do' => 'Ediləcəklər',
            'placeholder' => 'Nə edilməlidir ?',
            'new' => 'Sizə təyin edilmiş tapşırığa yeni bir iş əlavə edildi',
            'checked' => ':name :user tərəfindən yerinə yetirildi',
        ],
        'types' => [
            'assigned_to_me' => 'Mənə tapşırılıb',
            'my_tasks' => 'Mənim tapşırdıqlarım',
            'all' => 'Hamısı'
        ],
        'label' => [
            'name' => 'Tapşırıq Adı'
        ]
    ],

    'changes' => [
        'title' => 'Yeni bir dəyişiklik var'
    ],

    'works' => [
        'new' => 'Sizə yeni bir iş təyin edildi',
        'content' => [
            'user' => 'Sizə yeni iş verildi',
            'department' => 'Şöbənizə yeni bir iş verildi'
        ],
        'statuses' => [
            'rejected' => 'Sizin gördüyünüz iş təstiqlənmədi'
        ]
    ],

    'transactions' => [
        'types' => [
            1 => 'Məxaric',
            2 => 'Mədaxil'
        ],
        'statuses' => [
            1 => 'Uğurlu Ödəniş',
            2 => 'Geri Qaytarıldı',
        ],
        'methods' => [
            1 => 'Nağd',
            2 => 'Kart',
        ]
    ],

    'referrals' => [
        'link' => 'Sizin referal linkiniz',
        'sub_message' => 'Bu referal linkinizdir, kopyalayın və dostlarınızla paylaşın',
        'total' => 'Toplam referallar',
        'packages' => 'Bağlamalar',
        'earnings' => 'Qazanc',
        'bonus' => 'Bonus balansı',
        'get_data' => 'Referal məlumatlarını əldə etmək üçün',
        'send_req' => 'Sorğu göndərin',
        'updated' => 'Son yenilənmə',
        'retry_later' => ':count yenidən yoxlayın.'
    ],

    'updates' => [
        1 => 'Rədd edildi',
        'Gözləyən',
        'Qəbul edildi',
        'Başladı',
        'Bitdi',
        'Gələcək',
        'Xəta',
        'Səhv',
        'Həll olundu'
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
        'name' => 'Adı',
        'priority' => 'Prioritet',
        'status' => 'Status',
        'created_by' => 'Tapşırıq verən',
        'created_at' => 'Yaradıldı',
        'user' => 'İstifadəçi',
        'stage' => 'Mərhələ',
        'full_name' => 'Tam Adı',
        'fin' => 'FIN',
        'email' => 'Email',
        'phone' => 'Mobil Telefon',
        'company' => 'Şirkət',
        'creator' => 'Yaradan',
        'department' => 'Şöbə',
        'short_name' => 'Qısa Ad',
        'role' => 'Vəzifə',
        'actions' => 'Əməliyyatlar',
        'adress' => 'Ünvan',
        'gb' => 'Gb sayı',
        'code_count' => 'Kod sayı',
        'count_other' => 'Say',
        'e-receipt' => 'E-Qaimə',
        'permissions' => 'İcazələr',
        'type' => 'Növü',
        'order' => 'Sıralaması',
        'organization' => 'Qurum',
        'parent_option' => 'Ana parametr',
        'deadline' => 'Son tarix',
        'parameter_label' => 'Parameter Label',
        'verified' => 'Təsdiqlənib',
        'paid' => 'Əsas Məbləğ Ödənilib',
        'vat_paid' => 'ƏDV Ödənilib',
        'expired' => 'Vaxtı bitib',
        'total' => 'Toplam',
        'user_works' => 'İstifadəçinin Gördüyü İşlər',
        'rejected' => 'Qəbul edilməyib',
        'unverified' => 'Təsdiq edilməyib',
        'price_verified' => 'Qiymət təsdiqlənib',
        'price_unverified' => 'Qiymət təsdiqlənməyib',
        'reports_by_the_week' => 'Həftəyə görə hesabatlar',
        'detail' => 'Detallar',
        'attribute' => 'Atributlar',
        'internal_number' => 'Daxili Nömrə',
        'is_certificate' => 'Sertifikat',
        'sales_activity' => 'Satış Fəaliyyəti',
        'activity_area' => 'Fəaliyyət Sahəsi',
        'description' => 'Açıqlama',
        'hard_columns' => 'Əsas Sütunlar',
        'evaluation' => 'Qiymətləndirmə',
        'partner' => 'Partnyor',
        'folder' => 'Qovluq',
        'will_notify_at' => 'Bildirələcək',
        'will_end_at' => 'Bitəcək',
        'will_start_at' => 'Başlayacaq',
        'repeat_rate' => 'Repeat Rate',
        'class' => 'Kateqoriya',
        'title' => 'Başlıq',
        'date_time' => 'Görüş Vaxtı',
        'residue' => 'Borc',
        'sum_paid' => 'Toplam Ödənilən',
        'executant' => 'İcra Edən Şəxs',
        'url' => 'Url',
        'rate' => 'Xidmət Səviyyəsi',
        'price_rate' => 'Xidmət Haqqı',
        'amount' => 'Qiymət',
        'payment' => 'Ödəniş',
        'code' => 'Kod',
        'result' => 'Nəticə',
        'quality' => 'Məhsulun keyfiyyəti',
        'delivery' => 'Vaxtlı-vaxtında malların çatdırılması',
        'distributor' => 'Rəsmi distribyutor statusu',
        'availability' => 'Servis xidmətinin mövcudluğu',
        'certificate' => 'Materiala, avadanlığa uyğunluq sertifikatının olması',
        'support' => 'Texniki informasiya dəstəyi',
        'price' => 'Nəticə',
        'returning' => 'Artıq qalan mal-materialın geri təhvil vermə imkanı',
        'replacement' => 'Zədələnmiş mal-materialın əvəzlənməsi, geri təhvil vermə imkanı',
        'vat' => 'ƏDV',
        'last_paid' => 'Son Ödəmə Tarixi',
        'overhead_at' => 'Qaimə Tarixi',
        'overhead' => 'Qaimə',
        'supplier' => 'Tədarükçü',
        'salary' => 'Gəlir',
        'work_days' => 'İş Günləri',
        'actual_days' => 'Faktiki Günlər',
        'calculated_salary' => 'Hesablanmış Gəlir',
        'prize' => 'Mükafat',
        'vacation' => 'Məzuniyyət',
        'salary_tax' => 'Gəlir Vergis',
        'advance' => 'Avans',
        'sales' => 'Satış Əməkdaşı'
    ],

    'notify' => [
        'successfully' => 'Uğurlu',
        'processed_successfully' => 'proses uğurla tamamlandı',
        'sww' => 'Xəta baş verdi',
        'record' => 'Qeydiyyat',
        'retry_later' => ':count yenidən yoxlayın.'
    ],

    'users' => [
        'titles' => [
            'partner' => "Partnyor",
            'employee' => "Əməkdaş",
        ],
        'types' => [
            'employees' => 'Əməkdaşlar',
            'partners' => 'Ortaqlar',
            'all' => 'Hamısı'
        ],
        'statuses' => [
            'active' => 'Aktiv',
            'deactivate' => 'Deaktiv',
            'all' => 'Hamısı'
        ],

    ],

    'bonus' => [
        'effective' => 'Effektiv',
        'ineffective' => 'Karsız'
    ],

    'effectivity' => [
        1 => 'Effektiv',
        2 => 'Effektiv Deyil'
    ],

    'orders' => [
        'statuses' => [
            1 => 'Gözləmədə',
            2 => 'Hazırlanır',
            3 => 'Tamamlandı',
            4 => 'Rəddedildi',
        ],
        'payment' => [
            0 => 'Ödənməyib',
            1 => 'Ödənib',
        ]
    ],

    'creditors' => [
        'statuses' => [
            1 => 'Ödənilməyib',
            2 => 'Ödənilib',
            3 => 'Qismən Ödənilib',
        ]
    ],
    'employee_satisfactions' => [
        'satisfaction_types' => 'Məmnuniyyət Növləri',
        'is_enough' => 'Görülən tədbir yetərlidir mi?',
        'reason' => 'Düzəldici fəaliyyətin uğursuzluq səbəbi',
        'result' => 'Düzəldici fəaliyyətin nəticəsi',
        'effectivity' => 'Effektivlik',
        'incompatibility' => ':types aşkarlanıb',
        'more_time' => 'Əlavə zamana ehtiyac var mı?',
        'activity' => 'Düzəldici Fəaliyyət',
        'content-1' => 'Təklif və İstəyinizin Məzmununu Yazın',
        'content-2' => 'Qarşılaşdığınız Çətinliyin Məzmununu Yazın',
        'content-3' => 'Uyğunsuzluğun Məzmununu Yazın',
        'types' => [
            '1' => 'Təklif',
            '2' => 'Qarşılaşdığınız Çətinlik',
            '3' => 'Uyğunsuzluq'
        ],
        'statuses' => [
            '1' => 'Baxılmayıb',
            '2' => 'Araşdırılır',
            '3' => 'İcra Olunur',
            '4' => 'Tamamlandı',
            '5' => 'Rədd Edildi'
        ]
    ],

    'general' => [
        'inquirable' => 'Sorğulana Bilər',
        'sosials' => 'SOSYAL ŞƏBƏKƏLƏR',
        'sosial' => 'Sosyal Şəbəkə',
        'no_sosial_link' => 'Sosyal Şəbəkə Yoxdur',
        'no_users' => 'İstifadəçi Yoxdur',
        'common' => 'Ümumi',
        'no_service_parameter' => 'Xidmət Parametri Yoxdur',
        'sosial_url' => 'Sosyal Şəbəkə Linki',
        'empty' => 'Hələlik boşdur.',
        'not_found' => 'Tapılmadı!',
        'get_signature' => 'İmzamı Al',
        'signature_for' => 'İmza',
        'legal' => 'Hüquqi',
        'number' => 'Nömrə',
        'foreignlegal' => 'Xarici Hüquqi Şəxs',
        'physical' => 'Fiziki',
        'foreignphysical' => 'Xarici Vətəndaş',
        'typeChoose' => 'Müştəri Növü Seçilməyib',
        'activeChoose' => 'Müqaviləyə görə filterləmə',
        'earning' => 'Məbləğ',
        'currency' => 'Valyuta',
        'currency_rate' => 'Valyuta miqdarı (AZN)',
        'select_service' => 'Xidmət seçin',
        'work_service' => 'Xidmət',
        'work_service_type' => 'Xidmət növü',
        'rate' => 'Məzənnə(Manatla)',
        'work_earning' => 'Məbləğ',
        'work_detail' => 'Detal',
        'department_select' => 'Şöbə Seçin',
        'user_select' => 'Əməkdaş Seçin',
        'coordinator_select' => 'Koordinator Seçin',
        'sale_select' => 'Satış işçisi Seçin',
        'coordinator' => 'Koordinator',
        'sale' => 'Satış',
        'partner_select' => 'Partnyor Seçin',
        'position_select' => 'Vəzifə Seçin',
        'folder_select' => 'Qovluq Seçin',
        'select_client' => 'Müştəri Seçin',
        'done_at' => 'Bitirmə vaxtı',
        'verified_at' => 'Təstiqlənmə vaxtı',
        'started_at' => 'Başlama vaxtı',
        'hard_level_choose' => 'Çətinlik dərəcəsi Seçin',
        'status_choose' => 'Status Seçin',
        'format_choose' => 'Format Seçin',
        'club_choose' => 'Klub Seçin',
        'destination_choose' => 'Təyinat orqanı seçimi',
        'destination' => 'Təyinat orqanı',
        'hard_level' => 'Çətinlik dərəcəsi',
        'select_date' => 'Tarix Seç',
        'physical_client_name' => 'Hüquqi Müştərinin İşcilerinin Adı',
        'physical_client_mail' => 'Hüquqi Müştərinin İşcilerinin Emaili',
        'physical_client_phone' => 'Hüquqi Müştərinin İşcilerinin Nömrəsi',
        'physical_client_position' => 'Hüquqi Müştərinin İşcilerinin Vəzifəsi',
        'all' => 'Hamısı',
        'client_data' => 'Müştəri Məlumatları',
        'currency_value' => 'Valyuta Miqdarı',
        'symbol' => 'Simvol',
        'satisfaction' => 'Məmnuniyət',
        'payment_method' => 'Ödəmə Üsulu',
        'no_announcement' => 'Oxunmamış elan yoxdur',
        'no_message' => 'Oxunmamış mesaj yoxdur',
        'mark_as_read' => 'Oxudum',
        'mark_all' => 'Hamısını Oxudum',
        'declaration' => 'Bəyannamə',
        'all_departments' => 'Bütün Şöbələr',
        'accepted' => 'Qəbul Olundu',
        'paid' => 'Ödənilib',
        'transport_type' => 'Daşınma Növü',
        'transport_type_choose' => 'Daşınma Növü Seçin',
    ],

    'clients' => [
        'detail_empty' => 'Əlavə məlumat yoxdur',
        'phone_empty' => 'Nömrə yoxdur',
        'email_empty' => 'Email adresi  yoxdur',
        'voen_empty' => 'VOEN yoxdur',
        'add_representative' => 'Nümayəndə əlavə et',
        'selectUser' => 'Satış İstifadəçisi Seç',
        'selectCompany' => 'Şirkət Seçin',
        'selectCoordinator' => 'Koordinator Seçin',
        'selectSale' => 'Satış işçisi Seçin',
    ],

    'clients_type' => [
        'Hüquqi',
        'Fiziki',
        'Xarici Fiziki',
        'Xarici Hüquqi',
    ],

    'work_status' => [
        1 => 'Planlanan',
        'Gözləmədə',
        'Davam Edir',
        'Sistemə Vuruldu',
        'Geri Qaytarıldı',
        'Buraxılışda',
        'Tamamlandı',
        'Ləğv Olundu',
        'Laboratoriyada',
    ],

    'work_destination' => [
        1 => '14000 Aksizli mallar üzrə BGİ',
        '00100 Bakı BGİ',
        '00800 HNBGİ',
        '00118 Xocahəsən g/p',
        '00700 Sumqayıt Gİ',
        '13000 Dəniz nəqliyyatı və Enerji resursları Baş Gömrük İdarəsi',
        '00500 Balakən G/İ',
        '03400 “Şirvan” gömrük postu',
        '01200 Biləsuvar G/İ',
        '00900 Gəncə G/İ',
        '01400 Xaçmaz Gömrük İdarəsi',
        '17001 Xudafərin Gömrük Postu',
        '00905 Yevlax Gömrük Postu',
        '18000 Naxçıvan BGİ',
        '00123 Bakı KOB g/p',
        '00110 Sahil g/p',
        '00808 Poçt göndərişləri G/P',
        '00121 Abşeron G/P'
    ],

    'client_channels' => [
        1 => 'Dost/tanış',
        'Satış meneceri zəng edib',
        'Web sayt',
        'Instagram',
        'Facebook',
        'LinkedIn',
        'YouTube',
        'Heritus Logistics',
        'Arkas Logistics',
        'AMBGI',
        'BBGI',
        'HNBGI'
    ],

    'logistics_statuses' => [
        1 => 'Yük götürülüb',
        'Prosesdədir',
        'Yoldadır',
        'Çatıb',
        'Yük Dayandırılıb'
    ],
    'summit_status' => [
        1 => 'İştirak Edildi',
        'Təxirə Salındı',
        'Gözləmədə',
    ],
    'summit_clubs' => [
        1 => 'Caspian Energy Club',
        'Marsol Group',
        'TUİB ',
        'Networking Azerbaijan',
        'Founder Club',
        'AHK Azerbaijan',
        'KOBSKA',
        'ASK',
        'MÜSİAD',
        'İşgüzar',
    ],
    'summit_formats' => [
        1 => 'Canlı',
        'Online',
    ],

    'transport_types' => [
        1 => 'Quru Yolu Daşınması',
        'Hava Yolu Daşınması',
        'Dəniz Yolu Daşınması',
        'Dəmir Yolu Daşınması',
    ],

    'payment_methods' => [
        1 => 'Nəğd',
        'Bank',
        'PBank'
    ],

    'satisfactions' => [
        1 => 'Məmnun',
        'Məmnun Deyil',
        'Bilinmir',
    ],

    'hard_level' => [
        1 => 'Asan',
        'Orta',
        'Çətin',
    ],

    'files' => [
        'default_title' => 'Əlaqədar Fayllar',
        'contract' => 'Müqavilə',
        'personal_work' => 'Şəxsi İşlər'
    ],

    'calendar' => [
        'title' => 'Tədbir',
        'eventTypes' => 'Tədbir növləri',
        'fields' => [
            'select_type' => 'Tədbir növünü seçin',
            'name' => 'Tədbirin adı',
            'is_day_off' => 'İstirahət Günüdür',
            'is_repeatable' => 'Təkrarlanır',
        ],
        'types' => [
            1 => 'İş günü',
            'Bayram',
            'Tətil',
            'Ad günü',
            'Digər'
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
        'note' => 'Təklif, İrad və Şikayətləriniz',
    ],

    'registration_logs' => [
        'title' => 'Sizə yeni bir sənəd göndərilib',
        'content' => 'Sizin baxılmamış sənədiniz var',
    ],

    'reports' => [
        'check_new_chiefs' => 'Yeni rəhbərləri yoxlayın'
    ],
    'errors' => [
        '404' => 'Səhifə Tapılmadı',
        '403' => 'Sizin Bu Səhifəyə Daxil Olmaq Üçün İcazəniz Yoxdur',
        '503' => 'Hal-hazırda Sistemdə Profilaktik İşlər Gedir.Anlayışınız Üçün Təşəkkürlər',
    ],
    'sales_supply' =>[
        'sales_supply' => 'Satış Təchizatı',
        'supply_name' => 'Təchizat Adı',
        'supply_value' => 'Təchizat Dəyəri',
    ],
    'widgets' => [
        'number_of_users' => 'İstifadəçi Sayı',
        'number_of_inquiries' => 'Sorğu Sayı',
        'number_of_works' => 'İşlərin Sayı',
        'number_of_tasks' => 'Tapşırıqların Sayı',
        'welcome_msg' => 'Sizə Xoş Gün Arzulayırıq',
        'you_have' => 'Sizin :count tapşırığınız var '
    ],
    'chats' => [
        'title' => 'Sizə bir mesaj göndərildi',
    ],
    'client_active' => [
        0 => 'Qeyri-Aktiv',
        1 => 'Aktiv'
    ]
];
