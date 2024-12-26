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
        Schema::create('classes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('status')->default(App\Enums\Status::Enable);
            $table->string('type')->default(App\Enums\ClassType::Basic);
            $table->unsignedBigInteger('faculty_id')->index();
            $table->unsignedBigInteger('major_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
