<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attributes')->insert([
            [
                'name' => 'CPU',
                'type' => 'text',
                'unit' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'GPU',
                'type' => 'text',
                'unit' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'RAM',
                'type' => 'text',
                'unit' => 'GB',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'SSD',
                'type' => 'text',
                'unit' => 'GB',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'HDD',
                'type' => 'text',
                'unit' => 'GB',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Màn hình',
                'type' => 'text',
                'unit' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Pin',
                'type' => 'text',
                'unit' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'name' => 'Trọng lượng',
                'type' => 'text',
                'unit' => 'kg',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
