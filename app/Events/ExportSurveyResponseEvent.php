<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportSurveyResponseEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public int $userId,
    ) {
    }

    public function broadcastOn()
    {
        return ['export-survey-response-channel.' . $this->userId];
    }

    public function broadcastAs()
    {
        return 'export-survey-response-event';
    }
}
