<?php

namespace Database\Seeders;

use App\Models\Inquiry\Operation;
use Illuminate\Database\Seeder;

class InquiryOperationSeeder extends Seeder
{
    public function run()
    {
        Operation::insert([
            array(
                'key' => "import",
                'name' => json_encode(['en' => 'Import', 'az' => 'İdxal']),
            ),
            array(
                'key' => "export",
                'name' => json_encode(['en' => 'Export', 'az' => 'İxrac']),
            ),
        ]);
    }
}
