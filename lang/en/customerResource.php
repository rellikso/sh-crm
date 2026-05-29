<?php

return [
    'model_label' => 'Customer',
    'plural_model_label' => 'Customers',
    'navigation_group' => 'Support',

    'fields' => [
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email address',
        'phone' => 'Phone number',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
    ],

    'validation' => [
        'phone_regex' => 'The phone number format is invalid. Use international format (e.g., +1000000000).',
    ],
];
