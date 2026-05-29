<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label(__('ticketResource.fields.customer')),

                TextEntry::make('subject')
                    ->label(__('ticketResource.fields.subject')),

                TextEntry::make('text')
                    ->label(__('ticketResource.fields.text'))
                    ->columnSpanFull(),

                TextEntry::make('status')
                    ->label(__('ticketResource.fields.status')),

                TextEntry::make('answered_at')
                    ->label(__('ticketResource.fields.answered_at'))
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label(__('ticketResource.fields.created_at'))
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->label(__('ticketResource.fields.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}