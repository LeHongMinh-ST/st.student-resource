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
            $table->string('email')->nullable()->change();
            $table->string('phone_number', 20)->nullable()->change();
            $table->string('identification_card_number', 100)->nullable()->change();
            $table->string('identification_issuance_place', 100)->nullable()->change();
            $table->date('identification_issuance_date')->nullable()->change();
            $table->string('course')->nullable()->change();
            $table->unsignedBigInteger('training_industry_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->string('email')->nullable(false)->change();
            $table->string('phone_number', 20)->nullable(false)->change();
            $table->string('identification_card_number', 100)->nullable(false)->change();
            $table->string('identification_issuance_place', 100)->nullable(false)->change();
            $table->date('identification_issuance_date')->nullable(false)->change();
            $table->string('course')->nullable(false)->change();
            $table->unsignedBigInteger('training_industry_id')->nullable(false)->change();
        });
    }
};
