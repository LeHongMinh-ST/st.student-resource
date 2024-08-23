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
        Schema::create('student_quits', function (Blueprint $table): void {
            $table->id();
            $table->text('note_quit')->nullable();
            $table->unsignedBigInteger('quit_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_quits');
    }
};
