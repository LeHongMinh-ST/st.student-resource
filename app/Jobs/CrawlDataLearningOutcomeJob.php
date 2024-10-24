<?php

namespace App\Jobs;

use App\Services\LearningOutcome\CrawlDataLearningOutcomeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class CrawlDataLearningOutcomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $code,
    )
    {
    }

    public function handle()
    {
        if ($this->code) {
            app(CrawlDataLearningOutcomeService::class)->crawlData($this->code);
        }
    }
}
