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
        Schema::create('learning_outcomes_detail', function (Blueprint $table): void {
            $table->id();
            $table->integer('order')->nullable();
            $table->string('subject_code')->nullable();
            $table->string('subject_name')->nullable();
            $table->integer('credits')->nullable();
            $table->integer('percent_test')->nullable();
            $table->integer('percent_exam')->nullable();
            $table->float('diligence_point')->nullable();
            $table->float('progress_point')->nullable();
            $table->float('exam_point')->nullable();
            $table->float('total_point_number')->index();
            $table->string('total_point_string')->index();
            $table->unsignedBigInteger('learning_outcomes_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_outcomes_detail');
    }
};
