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
        Schema::create('survey_periods', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('year', 7);
            $table->string('status', 20);
            $table->string('type', 50);
            $table->unsignedBigInteger('faculty_id')->index();
            $table->unsignedBigInteger('created_by')->index();
            $table->unsignedBigInteger('updated_by')->index();
            $table->timestamps();
        });

        Schema::create('survey_period_graduation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_period_id')->index();
            $table->unsignedBigInteger('graduation_ceremony_id')->index();
            $table->timestamps();
        });

        Schema::create('survey_period_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_period_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedInteger('number_mail_send')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_periods');
        Schema::dropIfExists('survey_period_graduation');
        Schema::dropIfExists('survey_period_student');
    }
};
