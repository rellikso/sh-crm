<?php

return [

    'title' => 'Жүйеге кіру',

    'heading' => 'Аккаунтыңызға кіріңіз',

    'actions' => [

        'register' => [
            'before' => 'немесе',
            'label' => 'жаңа тіркелгі құрыңыз',
        ],

        'request_password_reset' => [
            'label' => 'Құпия сөзді ұмыттыңыз ба?',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Электрондық пошта мекенжайы',
        ],

        'password' => [
            'label' => 'Құпия сөз',
        ],

        'remember' => [
            'label' => 'Мені есте сақтау',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'Кіру',
            ],

        ],

    ],

    'multi_factor' => [

        'heading' => 'Тұлғаңызды растаңыз',

        'subheading' => 'Жүйеге кіруді жалғастыру үшін тұлғаңызды растауыңыз керек.',

        'form' => [

            'provider' => [
                'label' => 'Қалай растағыңыз келеді?',
            ],

            'actions' => [

                'authenticate' => [
                    'label' => 'Кіру',
                ],

            ],

        ],

    ],

    'messages' => [

        'failed' => 'Пайдаланушы аты немесе құпия сөз қате.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Кіру әрекеттері тым көп',
            'body' => 'Қайталап көру үшін :seconds секунд күтіңіз.',
        ],

    ],

];
