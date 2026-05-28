<?php

namespace App\Http\Requests\Api;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SubmitTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx,zip,txt'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $customer = Customer::where('email', $this->input('email'))
                ->where('phone', $this->input('phone'))
                ->first();

            if ($customer) {
                $hasRecentTicket = Ticket::where('customer_id', $customer->id)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->exists();

                if ($hasRecentTicket) {
                    $validator->errors()->add('ticket', __('tickets.rate_limit'));
                }
            }
        });
    }
}