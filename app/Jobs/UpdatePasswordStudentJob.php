<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Student;
use App\Supports\StudentHelper;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdatePasswordStudentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {

            Student::chunkById(100, function ($students): void {
                foreach ($students as $student) {
                    $student->password = Hash::make($student->code);
                    $student->email = StudentHelper::makeEmailStudent($student->code);
                    $student->save();
                }
            });
            DB::commit();
            Log::info('Update password student success');
        } catch (Exception $e) {
            Log::error('Error job reply contact', [
                'error' => $e->getMessage()
            ]);
            DB::rollBack();
        }
    }
}
