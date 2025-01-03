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
        Schema::table('classes', function (Blueprint $table): void {
            if (!Schema::hasColumn('classes', 'training_industry_id')) {
                $table->unsignedBigInteger('training_industry_id')->nullable();
            }

            if (Schema::hasColumn('classes', 'major_id')) {
                $table->dropColumn('major_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table): void {
            if (Schema::hasColumn('classes', 'training_industry_id')) {
                $table->dropColumn('training_industry_id');
            }
            if (!Schema::hasColumn('classes', 'major_id')) {
                $table->unsignedBigInteger('major_id')->nullable();
            }
        });
    }
};
