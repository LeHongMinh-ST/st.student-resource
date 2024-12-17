<?php

declare(strict_types=1);

namespace App\Jobs;

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

            DB::table('students')->orderBy('id')->chunk(100, function ($students): void {
                $data = $students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'password' => Hash::make($student->code),
                        'email' => StudentHelper::makeEmailStudent($student->code)
                    ];
                });

                DB::table('students')->upsert($data->toArray(), ['id'], ['password']);
                Log::info('Update password student success 100 record');
            });
            DB::commit();
            Log::info('Update password student success');
        } catch (Exception $e) {
            Log::error('Error job reply contact', [
                'error' => $e->getCode()
            ]);
            DB::rollBack();
        }
    }
}
