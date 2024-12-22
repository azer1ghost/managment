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
                'class_attribute' => "col-12 col-md-8 mb-3",
                'style_attribute' => "height: 320px;",
                'details' => json_encode(['en' => 'Daily Inquires', 'az' => 'Günlük sorğular']),
                'order' => 6,
                'status' => 1
            ),
            array(
                'key' => "taskDone-widget",
                'class_attribute' => "col-12 mb-3",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'Tasks', 'az' => 'Tapşırıqlar']),
                'order' => 3,
                'status' => 0
            ),
            array(
                'key' => "bonusTotal-widget",
                'class_attribute' => "col-12 col-md-6 mb-3",
                'style_attribute' => "height: 420px;",
                'details' => json_encode(['en' => 'Total invites', 'az' => 'Ümumi dəvətlər']),
                'order' => 4,
                'status' => 1
            ),
            array(
                'key' => "client-widget",
                'class_attribute' => "col-12 col-md-6 mb-3",
                'style_attribute' => "height: 420px;",
                'details' => json_encode(['en' => 'Clients', 'az' => 'Müştərilər']),
                'order' => 5,
                'status' => 1
            ),
            array(
                'key' => "service-widget",
                'class_attribute' => "col-12 col-md-6 mb-3",
                'style_attribute' => "height: 320px;",
                'details' => json_encode(['en' => 'Services works', 'az' => 'Xidmətlərin işləri']),
                'order' => 2,
                'status' => 1
            ),
            array(
                'key' => "workMonthly-widget",
                'class_attribute' => "col-12 mb-3 col-md-6",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'Monthly works', 'az' => 'Aylıq işlər']),
                'order' => 7,
                'status' => 1
            ),
            array(
                'key' => "workPersonal-widget",
                'class_attribute' => "col-12 mb-3 col-md-6",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'Personal works', 'az' => 'Şəxsi işlər']),
                'order' => 8,
                'status' => 1
            ),
            array(
                'key' => "inquiryUser-widget",
                'class_attribute' => "col-12 mb-3",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'User\'s Inquiry', 'az' => 'Əməkdaşların Sorğuları']),
                'order' => 6,
                'status' => 1
            ),array(
                'key' => "inquiryPersonalMonthly-widget",
                'class_attribute' => "col-12 mb-3 col-md-6",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'Monthly Personal Inquiries', 'az' => 'Aylıq Şəxsi Sorğular']),
                'order' => 10,
                'status' => 1
            ),array(
                'key' => "inquiryPersonalDaily-widget",
                'class_attribute' => "col-12 mb-3 col-md-6",
                'style_attribute' => "height: 520px;",
                'details' => json_encode(['en' => 'Daily Personal Inquiries', 'az' => 'Həftəlik Şəxsi Sorğular']),
                'order' => 11,
                'status' => 1
            ),
        ]);
    }
}
