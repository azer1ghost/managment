<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */


    'accepted' => ':attribute qəbul edilməlidir',
    'active_url' => ':attribute doğru URL deyil',
    'after' => ':attribute :date tarixindən sonra olmalıdır',
    'after_or_equal' => ':attribute :date tarixi ilə eyni və ya sonra olmalıdır',
    'alpha' => ':attribute yalnız hərflərdən ibarət ola bilər',
    'alpha_dash' => ':attribute yalnız hərf, rəqəm və tire simvolundan ibarət ola bilər',
    'alpha_num' => ':attribute yalnız hərf və rəqəmlərdən ibarət ola bilər',
    'array' => ':attribute massiv formatında olmalıdır',
    'before' => ':attribute :date tarixindən əvvəl olmalıdır',
    'before_or_equal' => ':attribute :date tarixindən əvvəl və ya bərabər olmalıdır',
    'between' => [
        'numeric' => ':attribute :min ilə :max arasında olmalıdır',
        'file' => ':attribute :min ilə :max KB ölçüsü intervalında olmalıdır',
        'string' => ':attribute :min ilə :max simvolu intervalında olmalıdır',
        'array' => ':attribute :min ilə :max intervalında hissədən ibarət olmalıdır',
    ],
    'boolean' => ' :attribute doğru və ya yanlış ola bilər',
    'confirmed' => ' :attribute doğrulanması yanlışdır',
    'current_password' => ' Şifrə yanlışdır',
    'date' => ' :attribute tarix formatında olmalıdır',
    'date_equals' => ' :attribute tarix formatında olmalıdır',
    'date_format' => ' :attribute :format formatında olmalıdır',
    'different' => ' :attribute və :other fərqli olmalıdır',
    'digits' => ' :attribute :digits rəqəmli olmalıdır',
    'digits_between' => ' :attribute :min ilə :max rəqəmləri intervalında olmalıdır',
    'dimensions' => ' :attribute doğru şəkil ölçülərində deyil',
    'distinct' => ' :attribute dublikat qiymətlidir',
    'email' => ' :attribute doğru email formatında deyil',
    'ends_with' => ':attribute aşağıdakılardan biri ilə bitməlidir: :values.',
    'exists' => ' seçilmiş :attribute yanlışdır',
    'file' => ' :attribute fayl formatında olmalıdır',
    'filled' => ' :attribute qiyməti olmalıdır',
    'gt' => [
        'numeric' => ' :attribute :value-dən böyük olmalıdır.',
        'file' => ' :attribute :value kilobaytdan böyük olmalıdır.',
        'string' => ' :attribute :value xarakterdən böyük olmalıdır.',
        'array' => ' :attribute :value elementindən çox olmalıdır.',
    ],
    'gte' => [
        'numeric' => ' :attribute :value -ə bərabər və ya böyük olmalıdır.',
        'file' => ' :attribute :value kilobayta bərabər və ya böyük olmalıdır.',
        'string' => ' :attribute :value xarakterə bərabər və ya böyük olmalıdır.',
        'array' => ':attribute :value elementləri və ya daha çox olmalıdır.',
    ],
    'image' => ' :attribute şəkil formatında olmalıdır',
    'in' => ' seçilmiş :attribute yanlışdır',
    'in_array' => ' :attribute :other qiymətləri arasında olmalıdır',
    'integer' => ' :attribute tam ədəd olmalıdır',
    'ip' => ' :attribute İP adres formatında olmalıdır',
    'ipv4' => ' :attribute İPv4 adres formatında olmalıdır',
    'ipv6' => ' :attribute İPv6 adres formatında olmalıdır',
    'json' => ' :attribute JSON formatında olmalıdır',
    'lt' => [
        'numeric' => ':attribute :value-dən kiçik olmalıdır.',
        'file' => ':attribute :value kilobaytdan kiçik olmalıdır.',
        'string' => ':attribute :value xarakterdən kiçik olmalıdır.',
        'array' => ':attribute :value elementindən az olmalıdır..',
    ],
    'lte' => [
        'numeric' => ' :attribute :value -ə bərabər və ya kiçik olmalıdır.',
        'file' => ' :attribute :value kilobayta bərabər və ya kiçik olmalıdır.',
        'string' => ' :attribute :value xarakterə bərabər və ya kiçik olmalıdır.',
        'array' => ' :attribute :value -dən çox olmamalıdır.',
    ],
    'max' => [
        'numeric' => ' :attribute maksiumum :max rəqəmdən ibarət ola bilər',
        'file' => ' :attribute maksimum :max KB ölçüsündə ola bilər',
        'string' => ' :attribute maksimum :max simvoldan ibarət ola bilər',
        'array' => ' :attribute maksimum :max hədd\'dən ibarət ola bilər',
    ],
    'mimes' => ' :attribute :values tipində fayl olmalıdır',
    'mimetypes' => ' :attribute :values tipində fayl olmalıdır',
    'min' => [
        'numeric' => ' :attribute minimum :min rəqəmdən ibarət ola bilər',
        'file' => ' :attribute minimum :min KB ölçüsündə ola bilər',
        'string' => ' :attribute minimum :min simvoldan ibarət ola bilər',
        'array' => ' :attribute minimum :min hədd\'dən ibarət ola bilər',
    ],
    'multiple_of' => 'The :attribute :value -nin çoxluğu olmalıdır. ',

    'not_in' => ' seçilmiş :attribute yanlışdır',
    'not_regex' => 'The :attribute format is invalid.',

    'numeric' => ' :attribute rəqəmlərdən ibarət olmalıdır',
    'password' => 'Şifrə yanlışdır.',
    'present' => ' :attribute iştirak etməlidir',
    'regex' => ' :attribute formatı yanlışdır',
    'required' => ' :attribute doldurmaq mütləqdir',
    'required_if' => ' :attribute (:other :value ikən) mütləqdir',
    'required_unless' => ' :attribute (:other :values \'ə daxil ikən) mütləqdir',
    'required_with' => ' :attribute (:values var ikən) mütləqdir',
    'required_with_all' => ' :attribute (:values var ikən) mütləqdir',
    'required_without' => ' :attribute (:values yox ikən) mütləqdir',
    'required_without_all' => ' :attribute (:values yox ikən) mütləqdir',
    'prohibited' => ' :attribute sahəsi qadağandır.',
    'prohibited_if' => ' :attribute :other :value olduqda qadağandır.',
    'prohibited_unless' => ':attribute sahəsi :other :value daxilində olmadığı halda qadağandır.',
    'same' => ' :attribute və :other eyni olmalıdır',
    'size' => [
        'numeric' => ' :attribute :size ölçüsündə olmalıdır',
        'file' => ' :attribute :size KB ölçüsündə olmalıdır',
        'string' => ' :attribute :size simvoldan ibarət olmalıdır',
        'array' => ' :attribute :size hədd\'dən ibarət olmalıdır',
    ],
    'starts_with' => ':attribute aşağıdakılardan biri ilə başlamalıdır:  :values.',
    'string' => ' :attribute hərf formatında olmalıdır',


    'timezone' => ' :attribute ərazi formatında olmalıdır',
    'unique' => ' :attribute artıq iştirak edib',
    'uploaded' => ' :attribute yüklənməsi mümkün olmadı',
    'url' => ' :attribute formatı yanlışdır',
    'uuid' => ' :attribute doğru UUID olmalıdır.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    |  following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];