<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingPagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\LandingPage::count() == 0) {
            DB::table('landing_pages')->insert([
                "_id" => "l1",
                "name" => "Instapage 1",
                "platform" => "Instapage",
                "url" => "http://www.englishforthai.topicanative.co.th/",
                "description" => "Lp1",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('landing_pages')->insert([
                "_id" => "l2",
                "name" => "Instapage 2",
                "platform" => "Instapage",
                "url" => "http://www.english.topicanative.co.th/",
                "description" => "lp2",
                "is_active" => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
