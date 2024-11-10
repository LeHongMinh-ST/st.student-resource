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
        Schema::table('survey_period_student', function (Blueprint $table): void {
            $table->string('code_verify', 33)->nullable()->after('number_mail_send');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_period_student', function (Blueprint $table): void {
            $table->dropColumn('code_verify');
        });
    }
};
