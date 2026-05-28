<?php

namespace App\DTO;

use App\Http\Requests\Api\SubmitTicketRequest;

class TicketData
{
    /**
     * @param array<\Illuminate\Http\UploadedFile> $attachments
     */
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $subject,
        public readonly string $message,
        public readonly array $attachments = []
    ) {}

    /**
     * Create DTO from validated request data.
     */
    public static function fromRequest(SubmitTicketRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            email: $request->validated('email'),
            phone: $request->validated('phone'),
            subject: $request->validated('subject'),
            message: $request->validated('message'),
            attachments: $request->file('attachments') ?? []
        );
    }
}
