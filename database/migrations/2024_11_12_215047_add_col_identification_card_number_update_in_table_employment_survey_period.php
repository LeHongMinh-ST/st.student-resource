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
            $table->string('identification_card_number_update', 30)->nullable()->after('identification_card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->dropColumn('identification_card_number_update');
        });
    }
};
