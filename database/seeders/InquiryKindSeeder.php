<?php

namespace Database\Seeders;

use App\Models\Inquiry\Kind;
use Illuminate\Database\Seeder;

class InquiryKindSeeder extends Seeder
{
    public function run(): void
    {
         Kind::insert([
             array(
                'key' => "incompatible",
                'name' => json_encode(['en' => 'Incompatible',  'az' => 'Uyğunsuzluq']),
                'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "volume_weight",
                 'name' => json_encode(['en' => 'Volume Weight', 'az' => 'Həcmi Çəki']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "limit",
                 'name' => json_encode(['en' => 'Limit']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "courier",
                 'name' => json_encode(['en' => 'Courier', 'az' => 'Kuryer']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "return",
                 'name' => json_encode(['en' => 'Return', 'az' => 'İadə']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "lost",
                 'name' => json_encode(['en' => 'Lost Package', 'az' => 'İtmiş bağlama']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "customs",
                 'name' => json_encode(['en' => 'in Customs', 'az' => 'Saxlanc']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "warehouse",
                 'name' => json_encode(['en' => 'warehouse', 'az' => 'Xarici anbarda']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "filial",
                 'name' => json_encode(['en' => 'Filial', 'az' => 'Filialda']),
                 'inquiry_subjects_id' => 3
             ),
             array(
                 'key' => "price",
                 'name' => json_encode(['en' => 'Price', 'az' => 'Qiymət']),
                 'inquiry_subjects_id' => 3
             ),
         ]);
    }
}
