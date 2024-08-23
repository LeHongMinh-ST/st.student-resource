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
        Schema::create('excel_import_file_errors', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('excel_import_files_id')->nullable()->index();
            $table->unsignedBigInteger('row')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_import_file_errors');
    }
};
