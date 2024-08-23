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
        Schema::table('excel_import_file_records', function (Blueprint $table): void {
            $table->dropColumn('student_id');
        });

        Schema::table('class_students', function (Blueprint $table): void {
            $table->DateTime('end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excel_import_file_records', function (Blueprint $table): void {
            $table->unsignedBigInteger('student_id')->nullable();
        });
    }
};
