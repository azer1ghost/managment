<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            'Elektron GB-nin tərtib olunması xidməti',
            'Elektron Qısa İdxal GB-nin tərtib olunması xidməti',
            'CMR-in tərtib olunması xidməti',
            'TIRCARNET-in tərtib olunması xidməti',
            'Gömrük kodunun müəyyən edilməsi',
            'Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin hazırlanması',
            'Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin çeşidlənməsi',
            'Təmsil etdiyi şəxsin tapşırığı əsasında yüklərin və nəqliyyat vasitələrinin tam gömrük rəsmiləşdirilməsi',
            'Təmsil etdiyi şəxsin tapşırığı əsasında nəqliyyat vasitələrinin tam gömrük rəsmiləşdirilməsi(Tranzit qeydiyyat nişanının alınması)',
            'Gömrük rüsumlarının əvvəlcədən hesablanması',
            'Təmsilçilik xidməti',
            'Təmsilçilik (Sertifikatın alınması)',
            'Təmsilçilik (Tələb Olunan Sertifikatın alınması)',
            'Qismən Təmsilçilik xidməti',
            'Printerlərə texniki baxışın göstərilməsi',
            'Serverlərə texniki baxışın göstərilməsi',
            'Kompüterlərə texniki baxışın göstərilməsi',
            'İnvoysun Hazırlanması',
            'Məktubların hazırlanması',
            'Təmsil etdiyi şəxsin etibarnaməsi əsasında yüklərin gömrük anbarından çıxarılması',
            'Təmsil etdiyi şəxsin tapşırığı əsasında nəqliyyat vasitələrinin çıxarılması',
            'Gömrük rəsmiləşdirilməsinin həyata keçirilməsi zamanı gömrük məmuru ilə əlaqə yaradılması xidməti',
            'Gömrük rəsmiləşdirilməsi üçün lazım olan sənədlərin yoxlanılması',
            'Konsultasiya Xidməti',
            'Çəki listi(Packing List) Tərtib olunması',
            'Ərazi Xərci',
            'Gömrük təmsilçiliyi xidməti',
            'Etibarnamənin tərtib olunması',
            'Anbar və Terminal ödənişi',
            'Elektron Qısa İdxal GB-nin tərtib olunması xidməti (Əsas Vərəq)',
            'Elektron Qısa İdxal GB-nin tərtib olunması xidməti (Əlavə Vərəq)',
            'Sadələşdirilmiş Bəyannamə',
            'Gömrükxana xidməti və Elektron Gömrük Bəyannamələrinin tərtib olunması',
            'Subicarə xidməti',
            'Yüklərin təhvil-təslim aktı əsasında ünvana çatdırılması xidməti(Bakı şəhəri üzrə)',
            'Digər xidmət',
            'Elektron GB-nin tərtib olunması(əsas vərəq)',
            'Elektron GB-nin tərtib olunması(əlavə vərəq)',
            'Ərazi Xərci və Digər Ödənişlər',
            'Laboratoriya təmsilçiliyi',
            'Texniki xidmətlərin göstərilməsi',
            'Təsdiq edici sənəd hazırlanması xidməti',
            'Gigiyenik sertifikatın alınması xidməti',
            'AQTA sertifikatın alınması üçün müraciət xidməti',
            'Mənşə sertifikatı üçün müraciət xidməti',
            'Müvəqqəti saxlanc bəyannaməsinin tərtib olunması xidməti',
            'Müvəqqəti idxal ərizəsinin yazılması xidməti',
            'İnvoys ərizəsinin yazılması xidməti',
            'Barkod dəyişdirilməsi ərizəsinin yazılması xidməti',
            'Ad dəyişmə ərizəsinin yazılması İdxalatçının adı xidməti',
            'Gömrük idarəsinin (təyinat) dəyişdirilməsi ərizəsinin yazılması',
            'Öhdəlik ərizəsinin yazılması xidməti',
            'Laboratoriya ərizəsinin (şəxsi müraciət əsasında) yazılması xidməti',
            'Ümumi düzəliş ərizəsinin yazılması xidməti',
            'Gömrük bəyannaməsi, invoys və digər xərclər əsas götürülərək maya dəyərinin dəqiq hesablanması xidməti',
            'Təsdiqedici sənəd üçün müraciət',
        ];

        foreach ($services as $name) {
            ServiceType::firstOrCreate(['name' => $name]);
        }
    }
}
