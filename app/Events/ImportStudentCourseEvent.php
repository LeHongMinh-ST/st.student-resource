<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportStudentCourseEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string $message,
        public int $userId,
    ) {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('import-student-course-channel.' . auth()->id());
    }

    public function broadcastAs()
    {
        return 'import-student-course-event';
    }
}
