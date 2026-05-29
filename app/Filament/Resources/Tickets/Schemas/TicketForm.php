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
                // Clean drop-down select component driven by Backed Enum
                Select::make('status')
                    ->options(TicketStatus::class)
                    ->native(false) // Renders beautiful Tailwind select instead of raw browser HTML
                    ->required(),

                // Informational text-only placeholder, hidden if not answered yet
                Placeholder::make('answered_at')
                    ->label('Automated Response Time')
                    ->content(fn ($record) => $record?->answered_at ? $record->answered_at->format('Y-m-d H:i:s') : '-')
                    ->visible(fn ($record) => $record?->answered_at !== null),

                Placeholder::make('customer_name')
                    ->label('Customer Name')
                    ->content(fn ($record) => $record?->customer?->name),

                Placeholder::make('customer_email')
                    ->label('Customer Email')
                    ->content(fn ($record) => $record?->customer?->email),

                Placeholder::make('subject')
                    ->label('Subject')
                    ->content(fn ($record) => $record?->subject),

                Placeholder::make('text')
                    ->label('Message Content')
                    ->content(fn ($record) => $record?->text),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('attachments')
                    ->multiple()
                    ->downloadable()
                    ->deletable(false),
            ])
            ->columns(1);
    }
}
