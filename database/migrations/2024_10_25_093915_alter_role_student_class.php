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
        Schema::table('class_students', function (Blueprint $table): void {
            if (!Schema::hasColumn('class_students', 'role')) {
                $table->string('role')->default(App\Enums\StudentRole::Basic->value);
            }
        });

        Schema::table('students', function (Blueprint $table): void {
            if (Schema::hasColumn('students', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_students', function (Blueprint $table): void {
            if (Schema::hasColumn('class_students', 'role')) {
                $table->dropColumn('role');
            }
        });

        Schema::table('students', function (Blueprint $table): void {
            if (!Schema::hasColumn('students', 'role')) {
                $table->string('role')->default(App\Enums\StudentRole::Basic->value);
            }
        });
    }
};
