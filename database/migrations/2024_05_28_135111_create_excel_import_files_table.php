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
        Schema::create('excel_import_files', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('total_record')->nullable();
            $table->unsignedInteger('process_record')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_import_files');
    }
};
