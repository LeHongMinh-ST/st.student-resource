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
        Schema::create('zip_export_files', function (Blueprint $table): void {
            $table->id();
            $table->unsignedInteger('file_total');
            $table->unsignedInteger('process_total');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('survey_period_id')->index();
            $table->unsignedBigInteger('faculty_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('pdf_export_files', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('zip_export_file_id')->index();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zip_export_files');
        Schema::dropIfExists('pdf_export_files');
    }
};
