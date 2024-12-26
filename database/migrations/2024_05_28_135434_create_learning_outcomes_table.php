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
        Schema::create('learning_outcomes', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('semester')->nullable();
            $table->unsignedInteger('year_school_start')->nullable();
            $table->unsignedInteger('year_school_end')->nullable();
            $table->float('semester_average_10')->nullable();
            $table->float('semester_average_4')->nullable();
            $table->float('cumulative_average_10')->nullable();
            $table->float('cumulative_average_4')->nullable();
            $table->unsignedInteger('credits')->nullable();
            $table->unsignedInteger('cumulative_credits')->nullable();
            $table->string('type_average')->nullable();
            $table->unsignedBigInteger('student_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_outcomes');
    }
};
