<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quits', function (Blueprint $table) {
            if (Schema::hasColumn('quits', 'semester_id')) {
                $table->dropColumn('semester_id');
            }

            if (!Schema::hasColumn('quits', 'year')) {
                $table->integer('year')->nullable();
            }

            if (!Schema::hasColumn('quits', 'certification')) {
                $table->string('certification', 255)->nullable();
            }

            if (!Schema::hasColumn('quits', 'certification_date')) {
                $table->date('certification_date', 255)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quits', function (Blueprint $table) {
            if (!Schema::hasColumn('quits', 'semester_id')) {
                $table->unsignedBigInteger('semester_id');
            }

            if (Schema::hasColumn('quits', 'year')) {
                $table->dropColumn('year');
            }

            if (Schema::hasColumn('quits', 'certification')) {
                $table->dropColumn('certification');
            }

            if (Schema::hasColumn('quits', 'certification_date')) {
                $table->dropColumn('certification_date');
            }
        });

    }
};
