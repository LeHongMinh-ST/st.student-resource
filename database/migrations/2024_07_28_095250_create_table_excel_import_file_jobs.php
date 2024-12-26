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
        Schema::create('excel_import_file_jobs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('excel_import_file_id')->index();
            $table->unsignedBigInteger('job_id')->index();
            $table->timestamps();
        });

        Schema::table('excel_import_files', function (Blueprint $table): void {
            $table->dropColumn('job_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_import_file_jobs');
    }
};
