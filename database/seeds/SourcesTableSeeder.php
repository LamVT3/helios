<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Source::count() == 0) {
            DB::table('sources')->insert([
                '_id' => "1",
                'name' => 'Facebook',
                'description' => 'Facebook Ads Platform',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('sources')->insert([
                '_id' => "2",
                'name' => 'Google',
                'description' => 'Google Ads Platform',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
