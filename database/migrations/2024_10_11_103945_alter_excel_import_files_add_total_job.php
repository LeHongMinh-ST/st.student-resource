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
        Schema::table('excel_import_files', function (Blueprint $table): void {
            if (!Schema::hasColumn('excel_import_files', 'total_job')) {
                $table->integer('total_job')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excel_import_files', function (Blueprint $table): void {
            $table->dropColumn('total_job');
        });
    }
};
