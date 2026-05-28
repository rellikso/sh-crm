<?php

namespace App\Http\Controllers\Api\V1;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "SH CRM API Contract",
    version: "1.0.0",
    description: "API specification for Ticket Management and external Widget integration components."
)]
#[OA\Server(
    url: "/api/v1",
    description: "Current application environment (Dynamic domain)"
)]
class BaseV1OpenApi
{
    // Global API attributes container
}