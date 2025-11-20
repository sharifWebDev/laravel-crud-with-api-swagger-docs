<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use Illuminate\Database\Seeder;

class ApiKeySeeder extends Seeder
{
    public function run(): void
    {
        ApiKey::create([
            'key' => 'abcd232',
            'platform' => 'android',
            'is_active' => true,
        ]);

        ApiKey::create([
            'key' => 'xyz2234',
            'platform' => 'ios',
            'is_active' => true,
        ]);
    }
}
