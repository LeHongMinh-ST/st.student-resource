<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportStudentGraduateEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string $message,
        public int $userId,
    ) {
    }

    public function broadcastOn()
    {
        return ['import-student-graduation-channel.' . $this->userId];
    }

    public function broadcastAs()
    {
        return 'import-student-graduation-event';
    }
}
