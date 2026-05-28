<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasLabel, HasColor
{
    case New = 'new';
    case Answered = 'answered';
    case Closed = 'closed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::New => 'New',
            self::Answered => 'Answered',
            self::Closed => 'Closed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'danger',     // Red badge
            self::Answered => 'success', // Green badge
            self::Closed => 'gray',     // Gray badge
        };
    }
}
