<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'customer_id',
        'subject',
        'text',
        'status',
        'locale',
        'payload',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'answered_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
