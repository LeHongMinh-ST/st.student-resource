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
        Schema::create('reflects', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('subject');
            $table->string('status');
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflects');
    }
};
