<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('ticketResource.fields.id'))
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('ticketResource.fields.status'))
                    ->badge(),

                TextColumn::make('customer.name')
                    ->label(__('ticketResource.fields.customer'))
                    ->searchable(),

                TextColumn::make('subject')
                    ->label(__('ticketResource.fields.subject'))
                    ->searchable()
                    ->limit(40),

                TextColumn::make('created_at')
                    ->label(__('ticketResource.fields.submitted'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}