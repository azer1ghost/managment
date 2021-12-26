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
            ['order' => 1, 'type' => 'select', 'name' => 'subject', 'label' =>  json_encode(['en' => 'Subject', 'az' => 'Mövzusu']), 'placeholder' =>  json_encode(['en' => 'Choose Subject', 'az' => 'Mövzunu Seç']), 'option_id' => null],
            ['order' => 2, 'type' => 'select', 'name' => 'kind', 'label' =>  json_encode(['en' => 'Kind', 'az' => 'Növü']), 'placeholder' =>  json_encode(['en' => 'Choose Kind', 'az' => 'Növunü Seç']), 'option_id' => 2],
            ['order' => 3, 'type' => 'select', 'name' => 'source', 'label' =>  json_encode(['en' => 'Source', 'az' => 'Mənbə']), 'placeholder' =>  json_encode(['en' => 'Choose Source', 'az' => 'Mənbəni Seç']), 'option_id' => null],
            ['order' => 4, 'type' => 'select', 'name' => 'contact_method', 'label' =>  json_encode(['en' => 'Contact Method', 'az' => 'Əlaqə Vasitəsi']), 'placeholder' =>  json_encode(['en' => 'Choose Contact Method', 'az' => 'Əlaqə Vasitəsini Seç']), 'option_id' => null],
            ['order' => 5, 'type' => 'select', 'name' => 'status', 'label' =>  json_encode(['en' => 'Status', 'az' => 'Status']), 'placeholder' =>  json_encode(['en' => 'Choose Status', 'az' => 'Statusu Seç']), 'option_id' => null],
            ['order' => 6, 'type' => 'text',   'name' => 'customer_id', 'label' =>  json_encode(['en' => 'Client Code', 'az' => 'Müştəri Kodu']), 'placeholder' =>  json_encode(['en' => 'Enter Client Code', 'az' => 'Müştəri Kodu daxil edin']), 'option_id' => null],
            ['order' => 7, 'type' => 'text',   'name' => 'fullname', 'label' =>  json_encode(['en' => 'Fullname', 'az' => 'Ad Soyad']), 'placeholder' =>  json_encode(['en' => 'Enter Fullname', 'az' => 'Ad Soyad daxil edin']), 'option_id' => null],
            ['order' => 7, 'type' => 'text',   'name' => 'email', 'label' =>  json_encode(['en' => 'Email']), 'placeholder' =>  json_encode(['en' => 'Email']), 'option_id' => null],
            ['order' => 8, 'type' => 'text',   'name' => 'phone', 'label' =>  json_encode(['en' => 'Phone', 'az' => 'Telefon']), 'placeholder' =>  json_encode(['en' => 'Choose Phone', 'az' => 'Telefonu Seç']), 'option_id' => null],
            ['order' => 9, 'type' => 'select', 'name' => 'operation', 'label' =>  json_encode(['en' => 'Operation', 'az' => 'Əməliyyat']), 'placeholder' =>  json_encode(['en' => 'Choose Operation', 'az' => 'Əməliyyatı Seç']), 'option_id' => null],
        ]);
    }
}
