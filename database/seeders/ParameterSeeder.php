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
                'parameter_id' => null
            ),
            array(
                'type' => "subject",
                'name' => json_encode(['en' => 'Problem']),
                'parameter_id' => null
            ),
            array(
                'type' => "subject",
                'name' => json_encode(['en' => 'Technical support', 'az' => 'Texniki yardım']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Whatsapp']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Facebook']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Instagram']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Twitter']),
                'parameter_id' => null
            ),
            array(
                'type' => "source",
                'name' => json_encode(['en' => 'Linkedin']),
                'parameter_id' => null
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
                'parameter_id' => null
            ),
            array(
                'type' => "operation",
                'name' => json_encode(['en' => 'Export', 'az' => 'İxrac']),
                'parameter_id' => null
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Active', 'az' => 'Aktiv']),
                'parameter_id' => null
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Done', 'az' => 'Tamamlanıb']),
                'parameter_id' => null
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Rejected', 'az' => 'İmtina olunub']),
                'parameter_id' => null
            ),
            array(
                'type' => "status",
                'name' => json_encode(['en' => 'Unreachable', 'az' => 'Zəng Çatmır']),
                'parameter_id' => null
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Call', 'az' => 'Zəng']),
                'parameter_id' => null
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Whatsapp']),
                'parameter_id' => null
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Facebook']),
                'parameter_id' => null
            ),
            array(
                'type' => "contact_method",
                'name' => json_encode(['en' => 'Instagram']),
                'parameter_id' => null
            ),
        ]);
    }
}
