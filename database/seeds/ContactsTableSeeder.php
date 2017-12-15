<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Contact::count() == 0) {
            factory(\App\Contact::class, 10000)->create();
        }
    }
}
