<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Inquiry::insert([
            array(
                'date'      => now(),
                'time'      => now(),
                'client'    => "Azer",
                'fullname'  => 'Azer Memmedov',
                'phone'     => '55 379 10 39',
                'subject'   => 24,
                'kind'      => 10,
                'source'    => 3,
                'contact_method'    => 2,
                'operation'    => 17,
                'status'    => 20,
                'company_id'    => 2,
                'note'    => '',
                'user_id' => 1,
                'redirected_user_id' => 1,
            ),
        ]);
    }
}
