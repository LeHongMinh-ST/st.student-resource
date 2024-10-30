<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('training_industries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->index();
            $table->string('name');
            $table->string('status', 20);
            $table->string('description')->nullable();
            $table->unsignedBigInteger('faculty_id')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_industries');
    }
};
