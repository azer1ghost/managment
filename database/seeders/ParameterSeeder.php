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
            ['type' => 'select', 'name' => 'subject', 'label' =>  json_encode(['en' => 'Subject', 'az' => 'Mövzusu']), 'placeholder' =>  json_encode(['en' => 'Choose Subject', 'az' => 'Mövzunu Seç']), 'option_id' => null],
            ['type' => 'select', 'name' => 'kind', 'label' =>  json_encode(['en' => 'Kind', 'az' => 'Növü']), 'placeholder' =>  json_encode(['en' => 'Choose Kind', 'az' => 'Növunü Seç']), 'option_id' => 2],
            ['type' => 'select', 'name' => 'source', 'label' =>  json_encode(['en' => 'Source', 'az' => 'Mənbə']), 'placeholder' =>  json_encode(['en' => 'Choose Source', 'az' => 'Mənbəni Seç']), 'option_id' => null],
            ['type' => 'select', 'name' => 'contact_method', 'label' =>  json_encode(['en' => 'Contact Method', 'az' => 'Əlaqə Vasitəsi']), 'placeholder' =>  json_encode(['en' => 'Choose Contact Method', 'az' => 'Əlaqə Vasitəsini Seç']), 'option_id' => null],
            ['type' => 'select', 'name' => 'status', 'label' =>  json_encode(['en' => 'Status', 'az' => 'Status']), 'placeholder' =>  json_encode(['en' => 'Choose Status', 'az' => 'Statusu Seç']), 'option_id' => null],
            ['type' => 'text',   'name' => 'fullname', 'label' =>  json_encode(['en' => 'Fullname', 'az' => 'Ad Soyad']), 'placeholder' =>  json_encode(['en' => 'Enter Fullname', 'az' => 'Ad Soyad daxil edin']), 'option_id' => null],
            ['type' => 'text',   'name' => 'client_code', 'label' =>  json_encode(['en' => 'Client Code', 'az' => 'Müştəri Kodu']), 'placeholder' =>  json_encode(['en' => 'Enter Client Code', 'az' => 'Müştəri Kodu daxil edin']), 'option_id' => null],
        ]);
    }
}
