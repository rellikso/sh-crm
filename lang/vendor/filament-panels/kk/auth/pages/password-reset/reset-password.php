<?php

return [

    'title' => 'Құпия сөзді өзгерту',

    'heading' => 'Құпия сөзді өзгерту',

    'form' => [

        'email' => [
            'label' => 'Электрондық пошта мекенжайы',
        ],

        'password' => [
            'label' => 'Құпия сөз',
            'validation_attribute' => 'password',
        ],

        'password_confirmation' => [
            'label' => 'Құпия сөзді растаңыз',
        ],

        'actions' => [

            'reset' => [
                'label' => 'Құпия сөзді өзгерту',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'Құпия сөзді өзгерту әрекеттері тым көп',
            'body' => 'Қайталап көру үшін :seconds секунд күтіңіз.',
        ],

    ],

];
