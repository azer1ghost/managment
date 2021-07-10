<?php

namespace Database\Seeders;

use App\Models\Inquiry\Subject;
use Illuminate\Database\Seeder;

class InquirySubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Subject::insert([
            array(
                'key' => "info",
                'name' => json_encode(['en' => 'Info', 'az' => 'Malumat']),
                'company_id' => 4
            ),
            array(
                'key' => "problem",
                'name' => json_encode(['en' => 'Problem']),
                'company_id' => 4
            ),
            array(
                'key' => "technical",
                'name' => json_encode(['en' => 'Technical support', 'az' => 'Texniki yardım']),
                'company_id' => 4
            ),
        ]);
    }
}
