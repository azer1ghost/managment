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
        $inquiry = Inquiry::factory()->create();

        $inquiry->parameters()->syncWithoutDetaching([1 => ['option_id' => 3]]);
        $inquiry->parameters()->syncWithoutDetaching([2 => ['option_id' => 13]]);
        $inquiry->parameters()->syncWithoutDetaching([6 => ['value' => "Agalarov Elvin"]]);
    }
}
