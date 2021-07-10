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
            InquirySubjectSeeder::class,
            InquiryKindSeeder::class,
            InquiryOperationSeeder::class,
            InquirySourceSeeder::class,
            InquiryStatusSeeder::class,
            InquiryContactTypeSeeder::class,
        ]);
    }
}
