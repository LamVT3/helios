<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Campaign::count() == 0) {
            DB::table('campaigns')->insert([
                "_id" => "1",
                "name" => "FA_Camp_01",
                "medium" => "Conversion",
                "source_id" => "1",
                "source_name" => "Facebook",
                "team_id" => "1",
                "team_name" => "FA_TET_01",
                "creator_id" => "1",
                "creator_name" => "admin",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('campaigns')->insert([
                "_id" => "2",
                "name" => "GG_Camp_01",
                "medium" => "ADW",
                "source_id" => "2",
                "source_name" => "Google",
                "team_id" => "2",
                "team_name" => "GG_TET_01",
                "creator_id" => "2",
                "creator_name" => "admin",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
