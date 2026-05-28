<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'metrics' => [
                'day' => $this['day'],
                'week' => $this['week'],
                'month' => $this['month'],
            ],
        ];
    }
}
