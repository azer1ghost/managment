<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyBankAccount;
use Illuminate\Database\Seeder;

class CompanyBankAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['slug'=>'mbrokerKapital','label'=>'Mobil Broker - Kapital Bank','company'=>'Mobil Broker','display'=>'"Mobil Broker" MMC','voen'=>'1804705371','hh'=>'AZ78AIIB400500D9447193478229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Vüsal Xəlilov İbrahim oğlu','who_footer'=>'V.İ.Xəlilov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mbroker1.jpeg'],
            ['slug'=>'mbrokerRespublika','label'=>'Mobil Broker - Bank Respublika','company'=>'Mobil Broker','display'=>'"Mobil Broker" MMC','voen'=>'1804705371','hh'=>'AZ17BRES00380394401114863601','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Vüsal Xəlilov İbrahim oğlu','who_footer'=>'V.İ.Xəlilov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mbroker1.jpeg'],
            ['slug'=>'mgroupRespublika','label'=>'Mobil Group - Bank Respublika','company'=>'Mobil Group','display'=>'"Mobil Group" MMC','voen'=>'1405261701','hh'=>'AZ31BRES00380394401115941601','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Vüsal Xəlilov İbrahim oğlu','who_footer'=>'V.İ.Xəlilov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mgroup1.jpeg'],
            ['slug'=>'garantKapital','label'=>'Garant Broker - Kapital Bank','company'=>null,'display'=>'"Garant Broker" MMC','voen'=>'1803974481','hh'=>'AZ56AIIB400500D9447227965229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Əhmədbəy İsmixanov Səfixan oğlu','who_footer'=>'Ə.S.İsmixanov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/gbroker1.jpeg'],
            ['slug'=>'garantRespublika','label'=>'Garant Broker - Bank Respublika','company'=>null,'display'=>'"Garant Broker" MMC','voen'=>'1803974481','hh'=>'AZ95BRES00380394401114875001','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Əhmədbəy İsmixanov Səfixan oğlu','who_footer'=>'Ə.S.İsmixanov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/gbroker1.jpeg'],
            ['slug'=>'rigelKapital','label'=>'Rigel Group - Kapital Bank','company'=>null,'display'=>'"Rigel Group" MMC','voen'=>'1805978211','hh'=>'AZ61AIIB400500E9445911817229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Xəlilova Lamiyə Fərhad qızı','who_footer'=>'L.İ.Xəlilova','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/rigel1.jpeg'],
            ['slug'=>'rigelRespublika','label'=>'Rigel Group - Bank Respublika','company'=>null,'display'=>'"Rigel Group" MMC','voen'=>'1805978211','hh'=>'AZ43BRES00380394401162048201','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Xəlilova Lamiyə Fərhad qızı','who_footer'=>'L.İ.Xəlilova','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/rigel1.jpeg'],
            ['slug'=>'tgroupKapital','label'=>'Tedora Group - Kapital Bank','company'=>null,'display'=>'"Tedora Group" MMC','voen'=>'1008142601','hh'=>'AZ06AIIB400500F9443614259229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Toğrul Surxayzadə Məhərrəm oğlu','who_footer'=>'T.M.Surxayzadə','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/tedora1.jpeg'],
            ['slug'=>'dgroupKapital','label'=>'Declare Group - Kapital Bank','company'=>null,'display'=>'"Declare Group" MMC','voen'=>'1406438851','hh'=>'AZ62AIIB400500F9443405268229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Mahir Həsənquliyev Tahir oğlu','who_footer'=>'M.T.Həsənquliyev','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/declare1.jpeg'],
            ['slug'=>'mindRespublika','label'=>'Mind Services - Bank Respublika','company'=>null,'display'=>'"Mind Services" MMC','voen'=>'1506046601','hh'=>'AZ88BRES00380394401162079401','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Musayev Ağarza Musarza oğlu','who_footer'=>'A.M.Musayev','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mind1.jpeg'],
            ['slug'=>'mindKapital','label'=>'Mind Services - Kapital Bank','company'=>null,'display'=>'"Mind Services" MMC','voen'=>'1506046601','hh'=>'AZ28AIIB400500E9444984575229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'Kapital Bank ASC KOB mərkəzi filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Musayev Ağarza Musarza oğlu','who_footer'=>'A.M.Musayev','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mind1.jpeg'],
            ['slug'=>'asazaRespublika','label'=>'ASAZA FLKS - Bank Respublika','company'=>null,'display'=>'"ASAZA FLKS" MMC','voen'=>'1805091391','hh'=>'AZ80BRES00380394401196199101','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Sabir Tahirov Zakir oğlu','who_footer'=>'S.Z.Tahirov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/asaza1.jpeg'],
            ['slug'=>'asazaKapital','label'=>'ASAZA FLKS - Kapital Bank','company'=>null,'display'=>'"ASAZA FLKS" MMC','voen'=>'1805091391','hh'=>'AZ79AIIB400500E9446021649229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'Kapital Bank ASC KOB mərkəzi filialı','bank_kod'=>'201412','bank_voen'=>'9900001901','swift'=>'AIIBAZ2XXXX','who'=>'Sabir Tahirov Zakir oğlu','who_footer'=>'S.Z.Tahirov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/asaza1.jpeg'],
            ['slug'=>'mtechnologiesRespublika','label'=>'Mobil Technologies - Bank Respublika','company'=>null,'display'=>'"Mobil Technologies" MMC','voen'=>'1804325861','hh'=>'AZ20BRES00380394401131856201','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Sabir Tahirov Zakir oğlu','who_footer'=>'S.Z.Tahirov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mtech1.jpeg'],
            ['slug'=>'mtechnologiesKapital','label'=>'Mobil Technologies - Kapital Bank','company'=>null,'display'=>'"Mobil Technologies" MMC','voen'=>'1804325861','hh'=>'AZ52AIIB400600D9447189871229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'Kapital Bank ASC KOB mərkəzi filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Sabir Tahirov Zakir oğlu','who_footer'=>'S.Z.Tahirov','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mtech1.jpeg'],
            ['slug'=>'logisticsKapital','label'=>'Mobil Logistics - Kapital Bank','company'=>'Mobil Logistics','display'=>'"Mobil Logistics" MMC','voen'=>'1804811521','hh'=>'AZ85AIIB400500D9447161910229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'KAPITAL BANK ASC KOB mərkəz filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Xəlilova Lamiyə Fərhad qızı','who_footer'=>'L.F.Xəlilova','representer'=>'Ekspeditor','stamp'=>'assets/images/finance/logistics1.jpeg'],
            ['slug'=>'logisticsRespublika','label'=>'Mobil Logistics - Bank Respublika','company'=>'Mobil Logistics','display'=>'"Mobil Logistics" MMC','voen'=>'1804811521','hh'=>'AZ77BRES00380394401116001301','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Xəlilova Lamiyə Fərhad qızı','who_footer'=>'L.F.Xəlilova','representer'=>'Ekspeditor','stamp'=>'assets/images/finance/logistics1.jpeg'],
            ['slug'=>'mobexRespublika','label'=>'Mobil Express - Bank Respublika','company'=>'Mobil Express','display'=>'"Mobil Express" MMC','voen'=>'1804892041','hh'=>'AZ55BRES40050AZ0111181435001','mh'=>'AZ80NABZ01350100000000014944','bank_name'=>"Bank Respublika ASC-nin 'Azadlıq' filialı",'bank_kod'=>'507547','bank_voen'=>'9900001901','swift'=>'BRESAZ22','who'=>'Həsənova Vüsalə İbrahim qızı','who_footer'=>'V.İ.Həsənova','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mobex1.jpeg'],
            ['slug'=>'mobexKapital','label'=>'Mobil Express - Kapital Bank','company'=>'Mobil Express','display'=>'"Mobil Express" MMC','voen'=>'1804892041','hh'=>'AZ64AIIB400500D9447160681229','mh'=>'AZ37NABZ01350100000000001944','bank_name'=>'Kapital Bank ASC KOB mərkəzi filialı','bank_kod'=>'201412','bank_voen'=>'9900003611','swift'=>'AIIBAZ2XXXX','who'=>'Həsənova Vüsalə İbrahim qızı','who_footer'=>'V.İ.Həsənova','representer'=>'Gömrük Təmsilçisi','stamp'=>'assets/images/finance/mobex1.jpeg'],
        ];

        foreach ($accounts as $data) {
            $companyName = $data['company'];
            $displayName = $data['display'];
            unset($data['company'], $data['display']);

            $companyId = null;
            if ($companyName) {
                $company = Company::where('name', 'like', "%{$companyName}%")->first();
                $companyId = $company?->id;
            }

            CompanyBankAccount::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'company_id' => $companyId,
                    'company_display_name' => $displayName,
                ])
            );
        }

        $this->command->info('Bank hesabları uğurla əlavə edildi.');
    }
}
