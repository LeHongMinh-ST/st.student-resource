<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\TrainingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_infos', function (Blueprint $table): void {
            $table->id();
            $table->string('person_email')->nullable();
            $table->string('gender')->default(Gender::Male);
            $table->string('permanent_residence')->nullable();
            $table->date('dob')->nullable();
            $table->string('pob')->nullable();
            $table->string('address')->nullable();
            $table->string('countryside')->nullable();
            $table->string('training_type')->default(TrainingType::FormalUniversity);
            $table->string('nationality')->nullable();
            $table->string('phone')->nullable();
            $table->string('citizen_identification')->nullable();
            $table->string('ethnic')->nullable();
            $table->string('religion')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('social_policy_object')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('student_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_infos');
    }
};
