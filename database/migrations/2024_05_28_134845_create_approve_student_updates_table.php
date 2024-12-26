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
        Schema::create('approve_student_updates', function (Blueprint $table): void {
            $table->id();
            $table->string('approveable_type')->nullable();
            $table->unsignedBigInteger('approveable_id')->index()->nullable();
            $table->string('status')->default(App\Enums\StudentInfoUpdateStatus::Pending);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approve_student_updates');
    }
};
