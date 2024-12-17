<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\UpdatePasswordStudentJob;
use App\Models\Student;
use App\Supports\StudentHelper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    public function handle(): int
    {
        $this->info('Dispatching jobs to update student passwords and emails...');

        // Chia nhỏ dữ liệu thành từng chunk 100 bản ghi
        Student::chunkById(100, function ($students) {
            // Đẩy mỗi chunk vào một job
            dispatch(new UpdatePasswordStudentJob($students));
            $this->info('Dispatched a job for 100 students.');
        });

        $this->info('All jobs have been dispatched successfully!');
    }
}
