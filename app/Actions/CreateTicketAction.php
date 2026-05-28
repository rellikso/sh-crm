<?php

namespace App\Actions;

use App\DTO\TicketData;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class CreateTicketAction
{
    /**
     * Execute the atomic ticket ingestion pipeline.
     */
    public function execute(TicketData $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            // 1. Manage customer identity state
            $customer = Customer::updateOrCreate(
                [
                    'email' => $data->email,
                    'phone' => $data->phone,
                ],
                [
                    'name' => $data->name,
                ]
            );

            // 2. Create ticket node bound to the customer
            /** @var Ticket $ticket */
            $ticket = $customer->tickets()->create([
                'subject' => $data->subject,
                'text' => $data->message,
            ]);

            // 3. Attach media components dynamically
            foreach ($data->attachments as $file) {
                $ticket->addMedia($file)->toMediaCollection('attachments');
            }

            return $ticket;
        });
    }
}
