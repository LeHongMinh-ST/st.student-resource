<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SystemAdmin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'user_name' => 'system_admin',
            'password' => '123456aA@',
        ];
        $admin = SystemAdmin::query()
            ->where('user_name', $data['user_name'])->first();
        if (empty($admin)) {
            SystemAdmin::query()->create($data);
        } else {
            $admin->update($data);
        }
    }
}
