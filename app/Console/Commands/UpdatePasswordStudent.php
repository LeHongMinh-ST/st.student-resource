<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdatePasswordStudentJob;
use Illuminate\Console\Command;

class UpdatePasswordStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-password-student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        UpdatePasswordStudentJob::dispatch()->onQueue('default');
    }
}
