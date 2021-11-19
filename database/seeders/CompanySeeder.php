<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::insert([
            array(
                'name' => "Mobil Group",
                'logo' => "logos/group.png",
                'website' => "mobilgroup.az",
                'mail' => 'info@mobilgroup.az',
                'call_center' => '*0090',
                'mobile' => '+994513339090',
                'address' => 'Bakı, Əhməd Rəcəbli 156',
                'about' => 'MOBİL GROUP gömrük brokeri xidmətləri və beynəlxalq daşımaları həyata keçirən şirkətlər qrupudur. Yerli və xarici vətəndaşlara, o cümlədən fiziki və hüquqi şəxslərə göstəridiyi geniş xidmət diapazonu ilə MOBİL GROUP özünü təsdiq etmiş yerli brenddir. MOBİL GROUP-un tərkibində beynəlxalq təcrübə əsasında qurulan “Mobil Broker” və “Mobil Logistics” fəaliyyət göstərir.',
                'keywords' => 'office',
                'is_inquirable' => '0'
            ),
            array(
                'name' => "Mobil Logistics",
                'logo' => "logos/logistics.png",
                'website' => "mobillogistics.az",
                'mail' => 'info@mobillogistics.az',
                'call_center' => '*0090',
                'mobile' => '+994513339090',
                'address' => 'Bakı, Əhməd Rəcəbli 156',
                'about' => 'Fake MOBİL GROUP məhsulu olan Mobil Broker MMC beynəlxalq təcrübə əsasında qurulan müasir gömrük təmsilçiliyidir. Mobil Broker 2017-cı ildən etibarən gömrük brokeri qismində fəaliyyətdədir. Böyük şəbəkəyə sahib olan şirkət kompleks gömrük xidmətləri təklif edir. Fiziki və ya hüquqi şəxslərə idxal və ixrac əməliyyatlarından yaxından köməklik edir.',
                'keywords' => 'logistics,shipping',
                'is_inquirable' => '1'
            ),
            array(
                'name' => "Mobil Broker",
                'logo' => "logos/broker.png",
                'website' => "mobilbroker.az",
                'mail' => 'info@mobilbroker.az',
                'call_center' => '*0090',
                'mobile' => '+994513339090',
                'address' => 'Bakı, Əhməd Rəcəbli 156',
                'about' => 'MOBİL GROUP məhsulu olan Mobil Broker MMC beynəlxalq təcrübə əsasında qurulan müasir gömrük təmsilçiliyidir. Mobil Broker 2017-cı ildən etibarən gömrük brokeri qismində fəaliyyətdədir. Böyük şəbəkəyə sahib olan şirkət kompleks gömrük xidmətləri təklif edir. Fiziki və ya hüquqi şəxslərə idxal və ixrac əməliyyatlarından yaxından köməklik edir.',
                'keywords' => 'documents',
                'is_inquirable' => '1'
            ),
            array(
                'name' => "Mobil Express",
                'logo' => "logos/express.png",
                'website' => "mobex.az",
                'mail' => 'info@mobex.az',
                'call_center' => '*7557',
                'mobile' => '+994513339090',
                'address' => 'Yasamal rayonu, Cəfər Cabbarlı 27.',
                'about' => 'Mobil Express MMC Türkiyə və Amerikadan hava yolu ilə Azərbaycana qısa müddətdə bağlamaların daşınması ilə məşğul olur. Hava nəqliyyatı məhsulların çatdırılmasının sürətli, təhlükəsiz və  əlverişli yolu hesab olunur və xidmətimizlə bağlamalarınızı qısa müddətdə əldə edə bilərsiniz.  Mobex xidmətlərinin yüksək keyfiyyəti və münasib qiymətləri ilə seçilir. ',
                'keywords' => 'cargo',
                'is_inquirable' => '1'
            ),
        ]);
    }
}
