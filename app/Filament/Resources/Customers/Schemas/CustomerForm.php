<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('customerResource.fields.name'))
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(100),

                TextInput::make('email')
                    ->label(__('customerResource.fields.email'))
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(table: 'customers', ignoreRecord: true),

                TextInput::make('phone')
                    ->label(__('customerResource.fields.phone'))
                    ->tel()
                    ->nullable()
                    ->regex('/^\+?[1-9]\d{6,14}$/')
                    ->validationMessages([
                        'regex' => __('customerResource.validation.phone_regex'),
                    ]),
            ])
            ->columns(1);
    }
}
