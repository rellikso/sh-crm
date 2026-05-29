<?php

return [

    'management_schema' => [

        'actions' => [

            'label' => 'Email арқылы растау',

            'below_content' => 'Жүйеге кіру кезінде растау үшін email мекенжайыңызға уақытша код алыңыз.',

            'messages' => [
                'enabled' => 'Қосылған',
                'disabled' => 'Өшірілген',
            ],

        ],

    ],

    'login_form' => [

        'label' => 'Кодты email-іңізге жіберу',

        'code' => [

            'label' => 'Біз сізге email арқылы жіберген 6 таңбалы кодты енгізіңіз',

            'validation_attribute' => 'код',

            'actions' => [

                'resend' => [

                    'label' => 'Email арқылы жаңа код жіберу',

                    'notifications' => [

                        'resent' => [
                            'title' => 'Біз сізге email арқылы жаңа код жібердік',
                        ],

                        'throttled' => [
                            'title' => 'Қайта жіберу әрекеттері тым көп. Басқа кодты сұрамас бұрын күте тұрыңыз.',
                        ],

                    ],

                ],

            ],

            'messages' => [

                'invalid' => 'Енгізілген код қате.',

            ],

        ],

    ],

];
