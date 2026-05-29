<?php

return [

    'title' => 'Тіркелу',

    'heading' => 'Тіркелгіні құру',

    'actions' => [

        'login' => [
            'before' => 'немесе',
            'label' => 'аккаунтыңызға кіру',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'Электрондық пошта мекенжайы',
        ],

        'name' => [
            'label' => 'Аты',
        ],

        'password' => [
            'label' => 'Құпия сөз',
            'validation_attribute' => 'password',
        ],

        'password_confirmation' => [
            'label' => 'Құпия сөзді растаңыз',
        ],

        'actions' => [

            'register' => [
                'label' => 'Тіркелу',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Тіркелу әрекеттері тым көп',
            'body' => 'Қайталап көру үшін :seconds секунд күтіңіз.',
        ],

    ],

];
