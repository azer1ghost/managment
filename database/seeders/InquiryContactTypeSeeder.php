<?php

namespace Database\Seeders;

use App\Models\Inquiry\ContactTypes;
use Illuminate\Database\Seeder;

class InquiryContactTypeSeeder extends Seeder
{
    public function run(): void
    {
        ContactTypes::insert([
            array(
                'key' => "call",
                'name' => json_encode(['en' => 'Call']),
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
            )
        ]);
    }
}
