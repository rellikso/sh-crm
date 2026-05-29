<?php

return [
    'model_label' => 'Клієнт',
    'plural_model_label' => 'Клієнти',
    'navigation_group' => 'Підтримка',

    'fields' => [
        'id' => 'ID',
        'name' => 'Ім\'я',
        'email' => 'Email адреса',
        'phone' => 'Номер телефону',
        'created_at' => 'Дата створення',
        'updated_at' => 'Дата оновлення',
    ],

    'validation' => [
        'phone_regex' => 'Невірний формат номера телефону. Використовуйте міжнародний формат (наприклад, +380000000000).',
    ],
];
