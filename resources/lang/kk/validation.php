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

    'accepted' => ':attribute қабылдануы керек.',
    'accepted_if' => ':other :value болған кезде :attribute қабылдануы керек.',
    'active_url' => ':attribute жарамды URL емес.',
    'after' => ':attribute :date күнінен кейінгі күні болуы керек.',
    'after_or_equal' => ':attribute :date күнінен кейінгі немесе тең күні болуы керек.',
    'alpha' => ':attribute тек әріптерден тұруы керек.',
    'alpha_dash' => ':attribute тек әріптер, сандар, сызықша және астынғы сызықтан тұруы керек.',
    'alpha_num' => ':attribute тек әріптер мен сандардан тұруы керек.',
    'array' => ':attribute массив болуы керек.',
    'before' => ':attribute :date күнінен бұрынғы күні болуы керек.',
    'before_or_equal' => ':attribute :date күнінен бұрынғы немесе тең күні болуы керек.',
    'between' => [
        'array' => ':attribute :min мен :max арасындағы элементтер саны болуы керек.',
        'file' => ':attribute :min мен :max арасындағы килобайт болуы керек.',
        'numeric' => ':attribute :min мен :max арасындағы сан болуы керек.',
        'string' => ':attribute :min мен :max арасындағы таңбалар саны болуы керек.',
    ],
    'boolean' => ':attribute логикалық түрде болуы керек.',
    'confirmed' => ':attribute растауы сәйкес келмейді.',
    'current_password' => 'Құпия сөз дұрыс емес.',
    'date' => ':attribute жарамды күн емес.',
    'date_equals' => ':attribute :date күніне тең болуы керек.',
    'date_format' => ':attribute :format форматына сәйкес келмейді.',
    'different' => ':attribute және :other әртүрлі болуы керек.',
    'digits' => ':attribute :digits сан болуы керек.',
    'digits_between' => ':attribute :min мен :max арасындағы сан болуы керек.',
    'dimensions' => ':attribute жарамсыз сурет өлшемдері бар.',
    'distinct' => ':attribute қайталанатын мән бар.',
    'email' => ':attribute жарамды электрондық пошта мекенжайы болуы керек.',
    'ends_with' => ':attribute келесі мәндердің бірімен аяқталуы керек: :values.',
    'enum' => 'Таңдалған :attribute жарамсыз.',
    'exists' => 'Таңдалған :attribute жарамсыз.',
    'file' => ':attribute файл болуы керек.',
    'filled' => ':attribute толтырылуы керек.',
    'gt' => [
        'array' => ':attribute :value элементтер санынан көп болуы керек.',
        'file' => ':attribute :value килобайттан көп болуы керек.',
        'numeric' => ':attribute :value мәнінен көп болуы керек.',
        'string' => ':attribute :value таңбалар санынан көп болуы керек.',
    ],
    'gte' => [
        'array' => ':attribute :value элементтер санынан көп немесе тең болуы керек.',
        'file' => ':attribute :value килобайттан көп немесе тең болуы керек.',
        'numeric' => ':attribute :value мәнінен көп немесе тең болуы керек.',
        'string' => ':attribute :value таңбалар санынан көп немесе тең болуы керек.',
    ],
    'image' => ':attribute сурет болуы керек.',
    'in' => 'Таңдалған :attribute жарамсыз.',
    'in_array' => ':attribute :other ішінде жоқ.',
    'integer' => ':attribute бүтін сан болуы керек.',
    'ip' => ':attribute жарамды IP мекенжайы болуы керек.',
    'ipv4' => ':attribute жарамды IPv4 мекенжайы болуы керек.',
    'ipv6' => ':attribute жарамды IPv6 мекенжайы болуы керек.',
    'json' => ':attribute жарамды JSON жолы болуы керек.',
    'lt' => [
        'array' => ':attribute :value элементтер санынан аз болуы керек.',
        'file' => ':attribute :value килобайттан аз болуы керек.',
        'numeric' => ':attribute :value мәнінен аз болуы керек.',
        'string' => ':attribute :value таңбалар санынан аз болуы керек.',
    ],
    'lte' => [
        'array' => ':attribute :value элементтер санынан аз немесе тең болуы керек.',
        'file' => ':attribute :value килобайттан аз немесе тең болуы керек.',
        'numeric' => ':attribute :value мәнінен аз немесе тең болуы керек.',
        'string' => ':attribute :value таңбалар санынан аз немесе тең болуы керек.',
    ],
    'max' => [
        'array' => ':attribute :max элементтер санынан көп болмауы керек.',
        'file' => ':attribute :max килобайттан көп болмауы керек.',
        'numeric' => ':attribute :max мәнінен көп болмауы керек.',
        'string' => ':attribute :max таңбалар санынан көп болмауы керек.',
    ],
    'mimes' => ':attribute келесі түрлердің бірі болуы керек: :values.',
    'mimetypes' => ':attribute келесі түрлердің бірі болуы керек: :values.',
    'min' => [
        'array' => ':attribute кемінде :min элементтер саны болуы керек.',
        'file' => ':attribute кемінде :min килобайт болуы керек.',
        'numeric' => ':attribute кемінде :min мәні болуы керек.',
        'string' => ':attribute кемінде :min таңбалар саны болуы керек.',
    ],
    'multiple_of' => ':attribute :value еселі болуы керек.',
    'not_in' => 'Таңдалған :attribute жарамсыз.',
    'not_regex' => ':attribute форматы жарамсыз.',
    'numeric' => ':attribute сан болуы керек.',
    'password' => [
        'letters' => ':attribute кемінде бір әріп болуы керек.',
        'mixed' => ':attribute кемінде бір бас әріп және бір кіші әріп болуы керек.',
        'numbers' => ':attribute кемінде бір сан болуы керек.',
        'symbols' => ':attribute кемінде бір таңба болуы керек.',
        'uncompromised' => 'Берілген :attribute деректердің ағында көрінді. :attribute жаңартыңыз.',
    ],
    'present' => ':attribute болуы керек.',
    'prohibited' => ':attribute тыйым салынған.',
    'prohibited_if' => ':other :value болған кезде :attribute тыйым салынған.',
    'prohibited_unless' => ':other :values ішінде болмаған кезде :attribute тыйым салынған.',
    'prohibits' => ':attribute :other болуын тыйым салады.',
    'regex' => ':attribute форматы жарамсыз.',
    'required' => ':attribute толтырылуы керек.',
    'required_array_keys' => ':attribute келесі жазбаларды қамтуы керек: :values.',
    'required_if' => ':other :value болған кезде :attribute толтырылуы керек.',
    'required_unless' => ':other :values тең болмаған кезде :attribute толтырылуы керек.',
    'required_with' => ':values көрсетілген кезде :attribute толтырылуы керек.',
    'required_with_all' => 'Барлық :values көрсетілген кезде :attribute толтырылуы керек.',
    'required_without' => ':values көрсетілмеген кезде :attribute толтырылуы керек.',
    'required_without_all' => ':values ешқайсысы көрсетілмеген кезде :attribute толтырылуы керек.',
    'same' => ':attribute және :other бірдей болуы керек.',
    'size' => [
        'array' => ':attribute :size элементтер саны болуы керек.',
        'file' => ':attribute :size килобайт болуы керек.',
        'numeric' => ':attribute :size мәні болуы керек.',
        'string' => ':attribute :size таңбалар саны болуы керек.',
    ],
    'starts_with' => ':attribute келесі мәндердің бірімен басталуы керек: :values.',
    'string' => ':attribute жол болуы керек.',
    'timezone' => ':attribute жарамды уақыт белдеуі болуы керек.',
    'unique' => ':attribute бұрыннан бар.',
    'uploaded' => ':attribute жүктелуі сәтсіз.',
    'url' => ':attribute форматы жарамсыз.',
    'uuid' => ':attribute жарамды UUID болуы керек.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute" to name the lines. This makes it quick to
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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'аты',
        'username' => 'пайдаланушы аты',
        'email' => 'электрондық пошта',
        'password' => 'құпия сөз',
        'password_confirmation' => 'құпия сөзді растау',
        'city' => 'қала',
        'country' => 'ел',
        'address' => 'мекенжай',
        'phone' => 'телефон',
        'mobile' => 'ұялы телефон',
        'age' => 'жасы',
        'sex' => 'жынысы',
        'gender' => 'жынысы',
        'day' => 'күні',
        'month' => 'айы',
        'year' => 'жылы',
        'hour' => 'сағаты',
        'minute' => 'минуты',
        'second' => 'секунды',
        'title' => 'тақырыбы',
        'content' => 'мазмұны',
        'description' => 'сипаттамасы',
        'excerpt' => 'үзінді',
        'date' => 'күні',
        'time' => 'уақыты',
        'available' => 'қолжетімді',
        'size' => 'өлшемі',
    ],

];
