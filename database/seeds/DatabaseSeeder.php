<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(LandingPagesTableSeeder::class);
        $this->call(SourcesTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(CampaignsTableSeeder::class);
        $this->call(SubcampaignsTableSeeder::class);
        $this->call(AdsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
    }
}
