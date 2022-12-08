<?php

namespace Database\Seeders;

use App\Models\Changelog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChangelogSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Changelog::insert(
            [
                [
                    'action' => 'create',
                    'new_payload' => (object)[
                        'name' => 'Samsung S22',
                        'price' => '4000',
                        'status' => true,
                        'product_type' => 1,
                        'created_by' => 1,
                        'created_at' => date('m/d/Y g:i A'),
                    ],
                    'old_payload' => []
                ],
                [
                    'action' => 'create',
                    'new_payload' => (object)[
                        'name' => 'iPhone 14',
                        'price' => '4500',
                        'status' => true,
                        'product_type' => 1,
                        'created_by' => 2,
                        'created_at' => date('m/d/Y g:i A'),
                    ],
                    'old_payload' => []
                ],
                [
                    'action' => 'create',
                    'new_payload' => (object)[
                        'name' => 'Samsung Watch 5',
                        'price' => '3000',
                        'status' => true,
                        'product_type' => 2,
                        'created_by' => 1,
                        'created_at' => date('m/d/Y g:i A'),
                    ],
                    'old_payload' => []
                ],
                [
                    'action' => 'create',
                    'new_payload' => (object)[
                        'name' => 'iWatch',
                        'price' => '3500',
                        'status' => true,
                        'product_type' => 2,
                        'created_by' => 2,
                        'created_at' => date('m/d/Y g:i A'),
                    ],
                    'old_payload' => []
                ],
            ]
        );
    }
}
