<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('approve_student_updates', function (Blueprint $table): void {
            if (!Schema::hasColumn('approve_student_updates', 'student_info_update_id')) {
                $table->unsignedBigInteger('student_info_update_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approve_student_updates', function (Blueprint $table): void {

        });
    }
};
