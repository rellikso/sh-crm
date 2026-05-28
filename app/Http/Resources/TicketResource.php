<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Disable standard 'data' wrapping root key for consistent structure if needed,
     * or handle manually within array tree.
     */
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'message' => 'Ticket has been successfully processed and recorded.',
            'data' => [
                'ticket_id' => $this->id,
            ],
        ];
    }
}
