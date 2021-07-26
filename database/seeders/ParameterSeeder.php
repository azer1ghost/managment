<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Seeder;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Parameter::insert([
            array(
                'type' => "subject",
                'name' => json_encode(['en' => 'Info', 'az' => 'Məlumat']),
            ),
            array(
                'type' => "subject",
                'name' => json_encode(['en' => 'Problem']),
            ),
            array(
                'type' => "subject",
                'name' => json_encode(['en' => 'Technical support', 'az' => 'Texniki yardım']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Whatsapp']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Facebook']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Instagram']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Twitter']),
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Linkedin']),
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Incompatible', 'az' => 'Uyğunsuzluq']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Volume Weight', 'az' => 'Həcmi Çəki']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Limit']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Courier', 'az' => 'Kuryer']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Return', 'az' => 'İadə']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Lost Package', 'az' => 'İtmiş bağlama']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'in Customs', 'az' => 'Saxlanc']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'warehouse', 'az' => 'Xarici anbarda']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Filial', 'az' => 'Filialda']),
                'parameter_id' => 2
            ),
            array(
                'type' => "kind",
                'name' => json_encode(['en' => 'Price', 'az' => 'Qiymət']),
                'parameter_id' => 2
            ),
            array(
                'type' => "operation",
                'name' => json_encode(['en' => 'Import', 'az' => 'İdxal']),
            ),
            array(
                'type' => "operation",
                'name' => json_encode(['en' => 'Export', 'az' => 'İxrac']),
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Active', 'az' => 'Aktiv']),
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Done', 'az' => 'Tamamlanıb']),
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Rejected', 'az' => 'İmtina olunub']),
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Unreachable', 'az' => 'Zəng Çatmır']),
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Whatsapp']),
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Facebook']),
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Instagram']),
            ),
        ]);
    }
}
