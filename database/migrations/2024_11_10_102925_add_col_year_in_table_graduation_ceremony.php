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
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            $table->unsignedInteger('year')->after('name')->nullable();
            $table->dropColumn('school_year_id');
        });

        Schema::table('admission_years', function (Blueprint $table): void {
            $table->string('school_year')->nullable()->change();
            $table->string('admission_year', 5)->index()->change();
        });

        Schema::table('students', function (Blueprint $table): void {
            $table->unsignedInteger('training_industry_id')->nullable()->after('faculty_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            $table->dropColumn('year');
            $table->unsignedBigInteger('school_year_id')->after('name')->nullable();
        });

        Schema::table('admission_years', function (Blueprint $table): void {
            $table->string('admission_year')->change();
            $table->dropIndex(['admission_year']);
        });

        Schema::table('students', function (Blueprint $table): void {
            $table->dropColumn('training_industry_id');
        });
    }
};
