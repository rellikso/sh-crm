<?php

namespace App\Http\Controllers\Api;

use App\Actions\CreateTicketAction;
use App\DTO\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubmitTicketRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TicketController extends Controller
{
    #[OA\Post(
        path: "/api/v1/tickets",
        summary: "Submit a new support ticket from external widget",
        description: "Creates or updates a customer node based on composite keys, logs an inbound ticket, and processes optional file attachments. Enforces a strict 24-hour rate limit per customer identity.",
        operationId: "submitTicket",
        tags: ["Tickets"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["name", "email", "phone", "subject", "message"],
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "John Doe", maxLength: 255),
                        new OA\Property(property: "email", type: "string", format: "email", example: "john.doe@example.com", maxLength: 255),
                        new OA\Property(property: "phone", type: "string", example: "+77012345678", maxLength: 50),
                        new OA\Property(property: "subject", type: "string", example: "Integration Error", maxLength: 255),
                        new OA\Property(property: "message", type: "string", example: "The iframe widget fails to load over custom SSL domains.", maxLength: 5000),
                        new OA\Property(
                            property: "attachments[]",
                            description: "Optional files to attach to the ticket (Max 10MB per file)",
                            type: "array",
                            items: new OA\Items(type: "string", format: "binary")
                        ),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Ticket successfully processed and recorded",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Ticket has been successfully processed and recorded."),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "ticket_id", type: "integer", example: 104)
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation failure, invalid file format, or 24-hour spam rate limit exceeded",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
                        new OA\Property(
                            property: "errors",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "attachments.0",
                                    type: "array",
                                    items: new OA\Items(type: "string", example: "The attachments.0 field must be a file of type: jpg, jpeg, png, pdf.")
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(SubmitTicketRequest $request, CreateTicketAction $action): JsonResponse
    {
        // Hydrate data container and offload processing execution to the domain layer
        $ticket = $action->execute(TicketData::fromRequest($request));

        return response()->json([
            'success' => true,
            'message' => 'Ticket has been successfully processed and recorded.',
            'data' => [
                'ticket_id' => $ticket->id,
            ],
        ], 201);
    }
}
