<?php

namespace App\Queries;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Carbon\CarbonInterface;

class GetTicketStatisticsQuery
{
    /**
     * Execute the query logic to aggregate ticket statistics.
     */
    public function execute(): array
    {
        return [
            'day' => $this->getMetricsForPeriod(now()->subDay()),
            'week' => $this->getMetricsForPeriod(now()->subWeek()),
            'month' => $this->getMetricsForPeriod(now()->subMonth()),
        ];
    }

    /**
     * Helper to assemble total and split status calculations.
     */
    private function getMetricsForPeriod(CarbonInterface $periodStart): array
    {
        $baseQuery = Ticket::createdAfter($periodStart);

        return [
            'total_tickets' => (clone $baseQuery)->count(),
            'new' => (clone $baseQuery)->withStatus(TicketStatus::New)->count(),
            'processing' => (clone $baseQuery)->withStatus(TicketStatus::Processing)->count(),
            'completed' => (clone $baseQuery)->withStatus(TicketStatus::Completed)->count(),
        ];
    }
}
