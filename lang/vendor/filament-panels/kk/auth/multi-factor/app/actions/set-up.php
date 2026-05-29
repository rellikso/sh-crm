<?php

return [

    'label' => 'Қосу',

    'modal' => [

        'heading' => '2FA қосымшасын баптау',

        'description' => <<<'BLADE'
            Процесті аяқтау үшін сізге Google Authenticator сияқты қосымша қажет болады (<x-filament::link href="https://itunes.apple.com/us/app/google-authenticator/id388497605" target="_blank">iOS</x-filament::link>, <x-filament::link href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">Android</x-filament::link>).
            BLADE,

        'content' => [

            'qr_code' => [

                'instruction' => 'Бұл QR-кодты 2FA қосымшасының көмегімен сканерлеңіз:',

                'alt' => '2FA қосымшасымен сканерлеуге арналған QR-код',

            ],

            'text_code' => [

                'instruction' => 'Немесе бұл кодты қолмен енгізіңіз:',

                'messages' => [
                    'copied' => 'Көшірілді',
                ],

            ],

            'recovery_codes' => [

                'instruction' => 'Келесі қалпына келтіру кодтарын қауіпсіз жерде сақтаңыз. Олар тек бір рет көрсетіледі, бірақ 2FA қосымшасына қолжетімділікті жоғалтқан жағдайда қажет болады:',

            ],

        ],

        'form' => [

            'code' => [

                'label' => '2FA қосымшасынан 6 таңбалы кодты енгізіңіз',

                'validation_attribute' => 'код',

                'below_content' => 'Жүйеге кірген сайын немесе құпия әрекеттерді орындаған кезде 2FA қосымшасынан 6 таңбалы кодты енгізуіңіз керек болады.',

                'messages' => [

                    'invalid' => 'Енгізілген код қате.',

                    'rate_limited' => 'Әрекеттер тым көп. Қайталап көруді кешірек орындаңыз.',
                ],

            ],

        ],

        'actions' => [

            'submit' => [
                'label' => '2FA қосымшасын қосу',
            ],

        ],

    ],

    'notifications' => [

        'enabled' => [
            'title' => '2FA қосымшасы қосылды',
        ],

    ],

];
