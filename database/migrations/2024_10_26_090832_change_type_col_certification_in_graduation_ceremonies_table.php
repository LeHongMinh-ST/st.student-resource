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
            $table->string('certification', 50)->change();
            $table->date('certification_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            $table->string('certification_date')->change();
        });
    }
};
