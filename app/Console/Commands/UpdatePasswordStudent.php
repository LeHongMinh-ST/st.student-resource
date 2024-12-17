<?php

declare(strict_types=1);

namespace App\Console\Commands;

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

                DB::table('students')->upsert($data->toArray(), ['id'], ['password', 'email']);
                Log::info('Update password student success 100 record');
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
