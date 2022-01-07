<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            SocialSeeder::class,
            RoleSeeder::class,
            OptionSeeder::class,
            ParameterSeeder::class,
            ParameterCompanySeeder::class,
            InquirySeeder::class,
//            TaskSeeder::class,
            DepartmentSeeder::class,
            OptionParameterSeeder::class,
            PositionSeeder::class,
            WidgetSeeder::class,
            UpdateSeeder::class,
            ClientSeeder::class,
            ServiceSeeder::class,
            AsanImzaSeeder::class,
            WorkSeeder::class,
            OrganizationSeeder::class,
            CertificateSeeder::class,
            SalesActivitiesTypeSeeder::class,
            SalesClientSeeder::class,
        ]);
    }
}
