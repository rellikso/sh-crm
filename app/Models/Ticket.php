<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'text',
        'status',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'answered_at' => 'datetime',
        ];
    }

    /**
     * Automate lifecycle timestamps based on status transitions.
     */
    protected static function booted(): void
    {
        static::updating(function (Ticket $ticket) {
            if ($ticket->isDirty('status') && $ticket->status === TicketStatus::Processing) {
                $ticket->answered_at = now();
            }
        });
    }

    /**
     * Scope to filter records created after a specific point in time.
     */
    public function scopeCreatedAfter($query, Carbon $date)
    {
        return $query->where('created_at', '>=', $date);
    }

    /**
     * Scope to quickly filter by status.
     */
    public function scopeWithStatus($query, TicketStatus $status)
    {
        return $query->where('status', $status->value);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
