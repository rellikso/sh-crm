<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketStatus;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label(__('ticketResource.fields.status'))
                    ->options(TicketStatus::class)
                    ->native(false)
                    ->required(),

                Placeholder::make('answered_at')
                    ->label(__('ticketResource.fields.answered_at'))
                    ->content(fn ($record) => $record?->answered_at ? $record->answered_at->format('Y-m-d H:i:s') : '-')
                    ->visible(fn ($record) => $record?->answered_at !== null),

                Placeholder::make('customer_name')
                    ->label(__('ticketResource.fields.customer_name'))
                    ->content(fn ($record) => $record?->customer?->name),

                Placeholder::make('customer_email')
                    ->label(__('ticketResource.fields.customer_email'))
                    ->content(fn ($record) => $record?->customer?->email),

                Placeholder::make('subject')
                    ->label(__('ticketResource.fields.subject'))
                    ->content(fn ($record) => $record?->subject),

                Placeholder::make('text')
                    ->label(__('ticketResource.fields.text'))
                    ->content(fn ($record) => $record?->text),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->label(__('ticketResource.fields.attachments'))
                    ->collection('attachments')
                    ->multiple()
                    ->downloadable()
                    ->deletable(false),
            ])
            ->columns(1);
    }
}