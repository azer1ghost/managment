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
            ['type' => 'select','name' => 'subject', 'label' =>  json_encode(['en' => 'Subject', 'az' => 'Mövzusu']), 'option_id' => null],
            ['type' => 'select','name' => 'kind', 'label' =>  json_encode(['en' => 'Kind', 'az' => 'Növü']), 'option_id' => 2],
            ['type' => 'select','name' => 'source', 'label' =>  json_encode(['en' => 'Source', 'az' => 'Mənbə']), 'option_id' => null],
            ['type' => 'select','name' => 'contact_method', 'label' =>  json_encode(['en' => 'Contact Method', 'az' => 'Əlaqə Vasitəsi']), 'option_id' => null],
            ['type' => 'select','name' => 'status', 'label' =>  json_encode(['en' => 'Status', 'az' => 'Status']), 'option_id' => null],
            ['type' => 'input','name' => 'fullname', 'label' =>  json_encode(['en' => 'Fullname', 'az' => 'Ad Soyad']), 'option_id' => null],
            ['type' => 'input','name' => 'client_code', 'label' =>  json_encode(['en' => 'Client Code', 'az' => 'Müştəri Kodu']), 'option_id' => null],
        ]);
    }
}
