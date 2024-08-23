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
        Schema::create('excel_import_file_records', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('excel_import_files_id')->nullable()->index();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->string('table_type')->nullable();
            $table->unsignedBigInteger('student_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_import_file_records');
    }
};
