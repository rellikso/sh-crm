<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasLabel, HasColor
{
    case New = 'new';
    case Processing = 'processing';
    case Completed = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::New => 'New',
            self::Processing => 'Processing',
            self::Completed => 'Completed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'danger',     // Red badge
            self::Processing => 'success', // Green badge
            self::Completed => 'gray',     // Gray badge
        };
    }
}
