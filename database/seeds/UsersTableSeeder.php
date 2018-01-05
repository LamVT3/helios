<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (\App\User::count() == 0) {
            DB::table('users')->insert([
                '_id' => "u1",
                'username' => 'admin',
                'fullname' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "s2" => [
                        'source_id' => "s2",
                        'source_name' => "Google",
                        'teams' => [
                            "t2" => [
                                "team_id" => "t2",
                                "team_name" => "GG_TET_01"
                            ]
                        ]
                    ]
                ],
                'is_active' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('users')->insert([
                '_id' => "u2",
                'username' => 'quangdh',
                'fullname' => 'QuangDH',
                'email' => 'quangdh@topica.edu.vn',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "s1" => [
                        'source_id' => "s1",
                        'source_name' => "Facebook",
                        'teams' => [
                            "t1" => [
                                "team_id" => "t1",
                                "team_name" => "FB_TET_01"
                            ]
                        ]
                    ]
                ],
                'is_active' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('users')->insert([
                '_id' => "u3",
                'username' => 'binhnq',
                'fullname' => 'BinhNQ',
                'email' => 'binhnq@topica.edu.vn',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "s2" => [
                        'source_id' => "s2",
                        'source_name' => "Google",
                        'teams' => [
                            "t2" => [
                                "team_id" => "t2",
                                "team_name" => "GG_TET_01"
                            ]
                        ]
                    ]
                ],
                'is_active' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            DB::table('users')->insert([
                '_id' => "4",
                'username' => 'anhbt2',
                'fullname' => 'Tuan Anh Bui',
                'email' => 'anhbt2@topica.edu.vn',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "s1" => [
                        'source_id' => "s1",
                        'source_name' => "Facebook",
                        'teams' => [
                            "t1" => [
                                "team_id" => "t1",
                                "team_name" => "FB_TET_01"
                            ]
                        ]
                    ]
                ],
                'is_active' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
