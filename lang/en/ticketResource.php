<?php

return [
    'model_label' => 'Ticket',
    'plural_model_label' => 'Tickets',
    'navigation_group' => 'Support',

    'fields' => [
        'id' => 'ID',
        'status' => 'Status',
        'customer' => 'Customer',
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'subject' => 'Subject',
        'text' => 'Message Content',
        'submitted' => 'Submitted',
        'answered_at' => 'Automated Response Time',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At',
        'attachments' => 'Attachments',
    ],
];
