<?php

namespace Database\Seeders;

use App\Models\Widget;
use Illuminate\Database\Seeder;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Widget::insert([
            array(
                'key' => "inquiryStatus-widget",
                'class_attribute' => "col-12 col-md-6 mb-3",
                'style_attribute' => "height: 320px;",
                'details' => json_encode(['en' => 'Status of Inquires', 'az' => 'Sorğuların statusu']),
                'order' => 1,
                'status' => 1
            ),
            array(
                'key' => "inquiryDaily-widget",
                'class_attribute' => "col-12 col-md-6 mb-3",
                'style_attribute' => "height: 320px;",
                'details' => json_encode(['en' => 'Daily Inquires', 'az' => 'Günlük sorğular']),
                'order' => 2,
                'status' => 1
            ),
        ]);
    }
}
