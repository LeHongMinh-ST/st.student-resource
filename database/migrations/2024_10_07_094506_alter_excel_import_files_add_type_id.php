<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('excel_import_files', function (Blueprint $table) {
            if (Schema::hasColumn('excel_import_files', 'admission_year_id')) {
                $table->renameColumn('admission_year_id', 'type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excel_import_files', function (Blueprint $table) {
            if (Schema::hasColumn('excel_import_files', 'type_id')) {
                $table->renameColumn('type_id', 'admission_year_id');
            }
        });
    }
};
