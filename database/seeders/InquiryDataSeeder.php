<?php

namespace Database\Seeders;

use App\Models\InquiryData;
use Illuminate\Database\Seeder;

class InquiryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InquiryData::insert([
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
            ),
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Whatsapp']),
            ),
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Facebook']),
            ),
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Instagram']),
            ),
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Twitter']),
            ),
            array(
                'key' => "source",
                'name' => json_encode(['en' => 'Linkedin']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Incompatible', 'az' => 'Uyğunsuzluq']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Volume Weight', 'az' => 'Həcmi Çəki']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Limit']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Courier', 'az' => 'Kuryer']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Return', 'az' => 'İadə']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Lost Package', 'az' => 'İtmiş bağlama']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'in Customs', 'az' => 'Saxlanc']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'warehouse', 'az' => 'Xarici anbarda']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Filial', 'az' => 'Filialda']),
            ),
            array(
                'key' => "kind",
                'name' => json_encode(['en' => 'Price', 'az' => 'Qiymət']),
            ),
            array(
                'key' => "operation",
                'name' => json_encode(['en' => 'Import', 'az' => 'İdxal']),
            ),
            array(
                'key' => "operation",
                'name' => json_encode(['en' => 'Export', 'az' => 'İxrac']),
            ),
            array(
                'key' => "status",
                'name' => json_encode(['en' => 'Active', 'az' => 'Aktiv']),
            ),
            array(
                'key' => "status",
                'name' => json_encode(['en' => 'Done', 'az' => 'Tamamlanıb']),
            ),
            array(
                'key' => "status",
                'name' => json_encode(['en' => 'Rejected', 'az' => 'İmtina olunub']),
            ),
            array(
                'key' => "status",
                'name' => json_encode(['en' => 'Unreachable', 'az' => 'Zəng Çatmır']),
            ),
            array(
                'key' => "subject",
                'name' => json_encode(['en' => 'Info', 'az' => 'Məlumat']),
            ),
            array(
                'key' => "subject",
                'name' => json_encode(['en' => 'Problem']),
            ),
            array(
                'key' => "subject",
                'name' => json_encode(['en' => 'Technical support', 'az' => 'Texniki yardım']),
            ),
        ]);
    }
}
