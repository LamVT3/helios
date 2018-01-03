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
                '_id' => "1",
                'username' => 'admin',
                'fullname' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "2" => [
                        'source_id' => "2",
                        'source_name' => "Google",
                        'teams' => [
                            "2" => [
                                "team_id" => "2",
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
                '_id' => "2",
                'username' => 'quangdh',
                'fullname' => 'QuangDH',
                'email' => 'quangdh@topica.edu.vn',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "1" => [
                        'source_id' => "1",
                        'source_name' => "Facebook",
                        'teams' => [
                            "1" => [
                                "team_id" => "1",
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
                '_id' => "3",
                'username' => 'binhnq',
                'fullname' => 'BinhNQ',
                'email' => 'binhnq@topica.edu.vn',
                'password' => bcrypt('topica@123'),
                'sources' => [
                    "2" => [
                        'source_id' => "2",
                        'source_name' => "Google",
                        'teams' => [
                            "2" => [
                                "team_id" => "2",
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
                    "1" => [
                        'source_id' => "1",
                        'source_name' => "Facebook",
                        'teams' => [
                            "1" => [
                                "team_id" => "1",
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
