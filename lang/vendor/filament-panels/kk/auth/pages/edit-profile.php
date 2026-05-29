<?php

return [

    'label' => 'Бейін',

    'form' => [

        'email' => [
            'label' => 'Электрондық пошта мекенжайы',
        ],

        'name' => [
            'label' => 'Аты',
        ],

        'password' => [
            'label' => 'Жаңа құпия сөз',
            'validation_attribute' => 'құпия сөз',
        ],

        'password_confirmation' => [
            'label' => 'Жаңа құпия сөзді растаңыз',
            'validation_attribute' => 'құпия сөзді растау',
        ],

        'current_password' => [
            'label' => 'Ағымдағы құпия сөз',
            'below_content' => 'Қауіпсіздік мақсатында жалғастыру үшін құпия сөзіңізді растаңыз.',
            'validation_attribute' => 'ағымдағы құпия сөз',
        ],

        'actions' => [

            'save' => [
                'label' => 'Өзгерістерді сақтау',
            ],

        ],

    ],

    'multi_factor_authentication' => [
        'label' => 'Екі факторлы аутентификация (2FA)',
    ],

    'notifications' => [

        'email_change_verification_sent' => [
            'title' => 'Email мекенжайын өзгерту туралы сұраныс жіберілді',
            'body' => 'Email мекенжайыңызды өзгерту туралы сұраныс :email поштасына жіберілді. Өзгерісті растау үшін поштаңызды тексеріңіз.',
        ],

        'saved' => [
            'title' => 'Сақталды',
        ],

        'throttled' => [
            'title' => 'Әрекеттер тым көп. Қайталап көру үшін :seconds секунд күтіңіз.',
            'body' => 'Қайталап көру үшін :seconds секунд күтіңіз.',
        ],

    ],

    'actions' => [

        'cancel' => [
            'label' => 'артқа',
        ],

    ],

];
