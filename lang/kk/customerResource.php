<?php

return [
    'model_label' => 'Клиент',
    'plural_model_label' => 'Клиенттер',
    'navigation_group' => 'Қолдау қызметі',

    'fields' => [
        'id' => 'ID',
        'name' => 'Аты',
        'email' => 'Email мекенжайы',
        'phone' => 'Телефон нөмірі',
        'created_at' => 'Құрылған күні',
        'updated_at' => 'Жаңартылған күні',
    ],

    'validation' => [
        'phone_regex' => 'Телефон нөмірінің форматы қате. Халықаралық форматты қолданыңыз (мысалы, +70000000000).',
    ],
];
