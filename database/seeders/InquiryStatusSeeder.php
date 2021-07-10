<?php

namespace Database\Seeders;

use App\Models\Inquiry\Status;
use Illuminate\Database\Seeder;

class InquiryStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            array(
                'key' => "active",
                'name' => json_encode(['en' => 'Active', 'az' => 'Aktiv']),
            ),
            array(
                'key' => "done",
                'name' => json_encode(['en' => 'Done', 'az' => 'Tamamlanıb']),
            ),
            array(
                'key' => "rejected",
                'name' => json_encode(['en' => 'Rejected', 'az' => 'İmtina olunub']),
            ),
            array(
                'key' => "unreachable",
                'name' => json_encode(['en' => 'Unreachable', 'az' => 'Zəng Çatmır']),
            ),
        ]);
    }
}
