<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('customerResource.fields.name')),

                TextEntry::make('phone')
                    ->label(__('customerResource.fields.phone')),

                TextEntry::make('email')
                    ->label(__('customerResource.fields.email')),

                TextEntry::make('created_at')
                    ->label(__('customerResource.fields.created_at'))
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label(__('customerResource.fields.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
