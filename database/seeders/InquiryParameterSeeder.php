<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Parameter;
use Illuminate\Database\Seeder;

class InquiryParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inquiry = Inquiry::all();
        $ids = Parameter::pluck('id');
        $inquiry->parameters()->sync($ids, ['option_id' => 4, 'value' => 'something']);
    }
}
