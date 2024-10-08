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
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->string('user_name');
            $table->string('email')->nullable();
            $table->string('code');
            $table->string('password');
            $table->string('school_year')->nullable();
            $table->string('admission_year')->nullable();
            $table->unsignedBigInteger('faculty_id')->index();
            $table->string('status')->default(App\Enums\StudentStatus::CurrentlyStudying);
            $table->string('role')->default(App\Enums\StudentRole::Basic);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
