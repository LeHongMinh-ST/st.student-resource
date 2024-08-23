<?php

declare(strict_types=1);

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable();
            $table->string('status')->default(Status::Enable);
            $table->string('description')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable()->index();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

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
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')
                    ->after('department_id')
                    ->index()->nullable();
            }

            if (!Schema::hasColumn('users', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false);
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('group_permissions');
        Schema::dropIfExists('roles');
    }
};
