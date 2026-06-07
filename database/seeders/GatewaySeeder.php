<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gateway::factory()->create([
            'title' => 'Shepa',
            'key' => 'shepa',
        ]);
        Gateway::factory()->create([
            'title' => 'Zarinpal',
            'key' => 'zarinpal',
        ]);
    }
}
