<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Team::count() == 0) {
            DB::table('teams')->insert([
                '_id' => "t1",
                "source_id"  => "s1",
                "source_name" => "Facebook",
                "name" => "FA_TET_01",
                "description" => "First facebook account",
                "members" => [
                    [
                        "user_id" => "u2",
                        "username" => "quangdh"
                    ],
                    [
                        "user_id" => "u4",
                        "username" => "anhbt2"
                    ]
                ],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('teams')->insert([
                '_id' => "t2",
                "source_id"  => "s2",
                "source_name" => "Google",
                "name" => "GG_TET_01",
                "description" => "First google account",
                "members" => [
                    [
                        "user_id" => "u1",
                        "username" => "admin"
                    ],
                    [
                        "user_id" => "u3",
                        "username" => "binhnq"
                    ]
                ],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
