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
        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->string('course', 10)->nullable()->after('training_industry_id');
            $table->dropColumn('admission_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->dropColumn('course');
            $table->unsignedBigInteger('admission_year_id')->nullable()->after('training_industry_id');
        });
    }
};
