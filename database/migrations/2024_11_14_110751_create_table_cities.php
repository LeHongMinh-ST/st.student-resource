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
        Schema::create('cities', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('priority')->default(0);
            $table->text('map_name')->nullable();
            $table->timestamps();
        });

        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->unsignedBigInteger('city_work_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
        Schema::table('employment_survey_responses', function (Blueprint $table): void {
            $table->dropColumn('city_work_id');
        });
    }
};
