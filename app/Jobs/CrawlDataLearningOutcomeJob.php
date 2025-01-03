<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\LearningOutcome\CrawlDataLearningOutcomeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CrawlDataLearningOutcomeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly string $code,
    ) {
    }

    public function handle(): void
    {
        if ($this->code) {
            app(CrawlDataLearningOutcomeService::class)->crawlData($this->code);
        }
    }
}
