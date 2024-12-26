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
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('group_permissions');
        Schema::dropIfExists('roles');
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role_id');
            $table->string('role')->nullable()->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code');
            $table->string('code_group');
            $table->string('status')->default(Status::Enable);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('permission_id')->index();
            $table->unsignedBigInteger('role_id')->index();
            $table->timestamps();
        });

        Schema::create('group_permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('role');
            $table->unsignedBigInteger('role_id')
                ->after('department_id')
                ->index()->nullable();
        });
    }
};
