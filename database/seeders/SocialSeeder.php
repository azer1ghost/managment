<?php

namespace Database\Seeders;

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Social::insert([
            array(
                'company_id' => "1",
                'name' => "facebook",
                'url' => "#",
            ),
            array(
                'company_id' => "1",
                'name' => "instagram",
                'url' => "#",
            ),
            array(
                'company_id' => "1",
                'name' => "youtube",
                'url' => "#",
            ),
        ]);
    }
}
