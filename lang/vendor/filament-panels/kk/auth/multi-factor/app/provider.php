<?php

return [

    'management_schema' => [

        'actions' => [

            'label' => '2FA қосымшасы',

            'below_content' => 'Жүйеге кіруді растау үшін уақытша кодты генерациялауға 2FA қосымшасын пайдаланыңыз.',

            'messages' => [
                'enabled' => 'Қосылған',
                'disabled' => 'Өшірілген',
            ],

        ],

    ],

    'login_form' => [

        'label' => '2FA қосымшаңыздағы кодты пайдаланыңыз',

        'code' => [

            'label' => '2FA қосымшасынан 6 таңбалы кодты енгізіңіз',

            'validation_attribute' => 'код',

            'actions' => [

                'use_recovery_code' => [
                    'label' => 'Қалпына келтіру кодын пайдалану',
                ],

            ],

            'messages' => [

                'invalid' => 'Енгізілген код қате.',

            ],

        ],

        'recovery_code' => [

            'label' => 'Немесе қалпына келтіру кодын енгізіңіз',

            'validation_attribute' => 'қалпына келтіру коды',

            'messages' => [

                'invalid' => 'Енгізілген қалпына келтіру коды қате.',

            ],

        ],

    ],

];
