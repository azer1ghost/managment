<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::insert([
            [ 'text' => json_encode(['en' => 'Info', 'az' => 'Məlumat'])],          
            [ 'text' => json_encode(['en' => 'Problem'])],                            
            [ 'text' => json_encode(['en' => 'Technical support', 'az' => 'Texniki yardım'])],
            [ 'text' => json_encode(['en' => 'Call', 'az' => 'Zəng'])],
            [ 'text' => json_encode(['en' => 'Whatsapp'])],                            
            [ 'text' => json_encode(['en' => 'Facebook'])],                            
            [ 'text' => json_encode(['en' => 'Instagram'])],                            
            [ 'text' => json_encode(['en' => 'Twitter'])],                         
            [ 'text' => json_encode(['en' => 'Linkedin'])],
            [ 'text' => json_encode(['en' => 'Volume Weight', 'az' => 'Həcmi Çəki'])],
            [ 'text' => json_encode(['en' => 'Limit'])],
            [ 'text' => json_encode(['en' => 'Courier', 'az' => 'Kuryer'])],
            [ 'text' => json_encode(['en' => 'Return', 'az' => 'İadə'])],
            [ 'text' => json_encode(['en' => 'Lost Package', 'az' => 'İtmiş bağlama'])],
            [ 'text' => json_encode(['en' => 'in Customs', 'az' => 'Saxlanc'])],
            [ 'text' => json_encode(['en' => 'Warehouse', 'az' => 'Xarici anbarda'])],
            [ 'text' => json_encode(['en' => 'Filial', 'az' => 'Filialda'])],
            [ 'text' => json_encode(['en' => 'Price', 'az' => 'Qiymət'])],
            [ 'text' => json_encode(['en' => 'Import', 'az' => 'İdxal'])],
            [ 'text' => json_encode(['en' => 'Export', 'az' => 'İxrac'])],
            [ 'text' => json_encode(['en' => 'Active', 'az' => 'Aktiv'])],
            [ 'text' => json_encode(['en' => 'Done', 'az' => 'Tamamlanıb'])],
            [ 'text' => json_encode(['en' => 'Rejected', 'az' => 'İmtina olunub'])],
            [ 'text' => json_encode(['en' => 'Incompatible', 'az' => 'Uyğunsuzluq'])],
            [ 'text' => json_encode(['en' => 'Unreachable', 'az' => 'Zəng Çatmır'])],
            [ 'text' => json_encode(['en' => 'Redirected', 'az' => 'Yönləndirildi'])],
        ]);
    }
}
