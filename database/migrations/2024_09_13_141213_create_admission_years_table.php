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
        Schema::create('admission_years', function (Blueprint $table): void {
            $table->id();
            $table->string('admission_year')->unique();
            $table->string('school_year');
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table): void {
            if (Schema::hasColumn('students', 'admission_year')) {
                $table->dropColumn('admission_year');
            }
            if (Schema::hasColumn('students', 'school_year')) {
                $table->dropColumn('school_year');
            }

            if (!Schema::hasColumn('students', 'admission_year_id')) {
                $table->unsignedBigInteger('admission_year_id');
            }
        });

        Schema::table('excel_import_files', function (Blueprint $table): void {
            if (!Schema::hasColumn('excel_import_files', 'admission_year_id')) {
                $table->unsignedBigInteger('admission_year_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_years');
    }
};
