<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cities;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = file_get_contents(base_path('database/DataInit/cities.json'));

        $data = json_decode($response, true);
        foreach ($data as $city) {
            $record = Cities::query()
                ->where('name', $city['name'])->first();
            if (empty($record)) {
                Cities::query()->create($city);
            } else {
                $record->update($city);
            }
        }
    }
}
