<?php

namespace Database\Seeders;

use App\Models\Inquiry\Source;
use Illuminate\Database\Seeder;

class InquirySourceSeeder extends Seeder
{
    public function run(): void
    {
       Source::insert([
            array(
                'key' => "call",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
            ),
            array(
                'key' => "whatsapp",
                'name' => json_encode(['en' => 'Whatsapp']),
            ),
            array(
               'key' => "facebook",
               'name' => json_encode(['en' => 'Facebook']),
            ),
            array(
               'key' => "instagram",
               'name' => json_encode(['en' => 'Instagram']),
            ),
            array(
               'key' => "twitter",
               'name' => json_encode(['en' => 'Twitter']),
            ),
            array(
               'key' => "linkedin",
               'name' => json_encode(['en' => 'Linkedin']),
            ),
        ]);
    }
}
