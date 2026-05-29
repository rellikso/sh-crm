<?php

namespace Tests\Feature\Api\V1;

use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Faking disk to safely test file downloads
        Storage::fake('public');
    }

    /** @test */
    public function test_it_can_submit_a_ticket_successfully_with_attachments(): void
    {
        $payload = [
            'name' => 'Alex Rellikso',
            'email' => 'rellikso@example.com',
            'phone' => '+77012345678',
            'subject' => 'Integration Issue',
            'message' => 'The widget layout breaks on smaller custom display frames.',
            'attachments' => [
                UploadedFile::fake()->image('screenshot.png'),
                UploadedFile::fake()->create('logs.pdf', 500)
            ]
        ];

        $response = $this->postJson('/api/v1/tickets', $payload, [
            'Accept-Language' => 'ru'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['ticket_id']
            ]);

        // Checking that client was created/found in database
        $this->assertDatabaseHas('customers', [
            'email' => 'rellikso@example.com',
            'phone' => '+77012345678'
        ]);

        // Checking that application has been added to database with "new" status
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Integration Issue',
            'status' => 'new'
        ]);
    }

    /** @test */
    public function test_it_enforces_validation_rules_on_ticket_submission(): void
    {
        $response = $this->postJson('/api/v1/tickets', [], [
            'Accept-Language' => 'ru'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'phone', 'subject', 'message']);
    }

    /** @test */
    public function test_it_enforces_strict_24_hour_rate_limit_per_identity(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'limit@example.com',
            'phone' => '+77019998877'
        ]);

        // Creating a request sent 5 hours ago.
        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => now()->subHours(5)
        ]);

        $payload = [
            'name' => 'Limit Test',
            'email' => 'limit@example.com',
            'phone' => '+77019998877',
            'subject' => 'Spam Attempt',
            'message' => 'This should be blocked by rate limiting logic.'
        ];

        $response = $this->postJson('/api/v1/tickets', $payload, [
            'Accept-Language' => 'ru'
        ]);

        $response->assertStatus(422);

        // Checking custom error message text
        $response->assertJsonPath('errors.ticket.0', 'Заявку можно отправить лишь раз в 24 часа.');
    }
}