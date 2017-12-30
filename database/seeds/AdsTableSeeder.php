<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Ad::count() == 0) {
            DB::table('ads')->insert([
                "_id" => "1",
                "name" => "FA_Camp_01_01_content",
                "source_id" => "1",
                "source_name" => "Facebook",
                "team_id" => "1",
                "team_name" => "FA_TET_01",
                "campaign_id" => "1",
                "campaign_name" => "FA_Camp_01",
                "subcampaign_id" => "1",
                "subcampaign_name" => "FA_Camp_01_01",
                "landingpage_id" => "1",
                "landingpage_name" => "Instapage 1",
                "tracking_link" => "http://www.englishforthai.topicanative.co.th/?utm_source=Facebook&utm_team=FA_TET_01&utm_agent=quangdh&utm_campaign=FA_Camp_01&utm_ad=FA_Camp_01_01_content",
                "short_link" => "http://goo.gl/kdAd0j",
                "creator_id" => "2",
                "creator_name" => "quangdh",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('ads')->insert([
                "_id" => "2",
                "name" => "GG_Camp_01_01_content",
                "source_id" => "2",
                "source_name" => "Google",
                "team_id" => "2",
                "team_name" => "GG_TET_01",
                "campaign_id" => "2",
                "campaign_name" => "GG_Camp_01",
                "subcampaign_id" => "2",
                "subcampaign_name" => "GG_Camp_01_01",
                "landingpage_id" => "2",
                "landingpage_name" => "Instapage 2",
                "tracking_link" => "http://www.englishforthai.topicanative.co.th/?utm_source=Google&utm_team=GG_TET_01&utm_agent=binhnq&utm_campaign=GG_Camp_01&utm_ad=GG_Camp_01_01_content",
                "short_link" => "http://goo.gl/kdAd0j",
                "creator_id" => "3",
                "creator_name" => "binhnq",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('ad_results')->insert([
                [
                    "ad_id" => "1",
                    "date" => "2017-12-27",
                    "c1" => 257920,
                    "c1_cost" => 36.32,
                    "c2" => 1149,
                    "c2_cost" => 8152.69,
                    "c3" => 127,
                    "c3_cost" => 73759,
                    "c3_duplicated" => 12,
                    "c3_invalid" => 6,
                    "c3b_cost" => 80231,
                    "c3bg" => 0,
                    "l1" => 0,
                    "l3" => 0,
                    "l8" => 0,
                    "returned_cts" => 0,
                    "revenue" => 0,
                    "spent" => 407,
                ],
                [
                    "ad_id" => "1",
                    "date" => "2017-12-26",
                    "c1" => 287920,
                    "c1_cost" => 42.32,
                    "c2" => 1349,
                    "c2_cost" => 8852.69,
                    "c3" => 202,
                    "c3_cost" => 61759,
                    "c3_duplicated" => 13,
                    "c3_invalid" => 3,
                    "c3b_cost" => 65231,
                    "c3bg" => 0,
                    "l1" => 0,
                    "l3" => 0,
                    "l8" => 0,
                    "returned_cts" => 0,
                    "revenue" => 0,
                    "spent" => 536,
                ],
                [
                    "ad_id" => "2",
                    "date" => "2017-12-27",
                    "c1" => 258920,
                    "c1_cost" => 36.32,
                    "c2" => 1149,
                    "c2_cost" => 8152.69,
                    "c3" => 127,
                    "c3_cost" => 73759,
                    "c3_duplicated" => 12,
                    "c3_invalid" => 6,
                    "c3b_cost" => 80231,
                    "c3bg" => 0,
                    "l1" => 0,
                    "l3" => 0,
                    "l8" => 0,
                    "returned_cts" => 0,
                    "revenue" => 0,
                    "spent" => 407,
                ],
                [
                    "ad_id" => "2",
                    "date" => "2017-12-26",
                    "c1" => 288920,
                    "c1_cost" => 42.32,
                    "c2" => 1349,
                    "c2_cost" => 8852.69,
                    "c3" => 202,
                    "c3_cost" => 61759,
                    "c3_duplicated" => 13,
                    "c3_invalid" => 3,
                    "c3b_cost" => 65231,
                    "c3bg" => 0,
                    "l1" => 0,
                    "l3" => 0,
                    "l8" => 0,
                    "returned_cts" => 0,
                    "revenue" => 0,
                    "spent" => 536,
                ]
            ]);
        }
    }
}
