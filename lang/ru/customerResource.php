<?php

return [
    'model_label' => 'Клиент',
    'plural_model_label' => 'Клиенты',
    'navigation_group' => 'Поддержка',

    'fields' => [
        'id' => 'ID',
        'name' => 'Имя',
        'email' => 'Email адрес',
        'phone' => 'Номер телефона',
        'created_at' => 'Дата создания',
        'updated_at' => 'Дата обновления',
    ],

    'validation' => [
        'phone_regex' => 'Неверный формат номера телефона. Используйте международный формат (например, +7000000000).',
    ],
];
