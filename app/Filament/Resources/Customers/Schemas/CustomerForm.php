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
                    ->required()
                    ->string()
                    ->minLength(2)
                    ->maxLength(100),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(table: 'customers', ignoreRecord: true),

                TextInput::make('phone')
                    ->tel()
                    ->nullable()
                    ->regex('/^\+?[1-9]\d{6,14}$/') // Strict E.164 API-like validation format
                    ->validationMessages([
                        'regex' => 'The phone number format is invalid. Use international format (e.g., +380000000000).',
                    ]),
            ])
            ->columns(1);
    }
}
