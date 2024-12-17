<?php

declare(strict_types=1);

namespace App\Console\Commands;

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
            return 0;
        } catch (Exception $e) {
            Log::error('Error job reply contact', [
                'error' => $e->getMessage()
            ]);
            DB::rollBack();
            return 1;
        }
    }
}
