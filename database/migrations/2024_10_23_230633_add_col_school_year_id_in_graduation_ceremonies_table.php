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
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            $table->unsignedBigInteger('school_year_id')->index()->nullable()->after('faculty_id');
            $table->dropColumn('school_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            $table->dropColumn('school_year_id');
            $table->string('school_year')->after('id')->nullable();
        });
    }
};
