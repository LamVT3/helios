<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Contact::class, function (Faker $faker) {
    $ad_id = $faker->numberBetween(1, 2);
    $ad = \App\Ad::find("a"."$ad_id");

    $current_level = $faker->numberBetween(0, 8);

    $is_valid = $current_level > 0 ? 2 : $faker->numberBetween(0, 1);

    $registered_date = $faker->dateTimeBetween($startDate = '-2 months', $endDate = 'now');

    $l1_time = $current_level > 0 ? $registered_date->modify('+1 day')->format('Y-m-d H:i:s') : '';
    $l2_time = $current_level > 1 ? \Carbon\Carbon::parse($l1_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l3_time = $current_level > 2 ? \Carbon\Carbon::parse($l2_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l4_time = $current_level > 3 ? \Carbon\Carbon::parse($l3_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l5_time = $current_level > 4 ? \Carbon\Carbon::parse($l4_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l6_time = $current_level > 5 ? \Carbon\Carbon::parse($l5_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l7_time = $current_level > 6 ? \Carbon\Carbon::parse($l6_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';
    $l8_time = $current_level > 7 ? \Carbon\Carbon::parse($l7_time."")->addDays($faker->numberBetween(0, 2))->toDateTimeString() : '';

    $is_returned = $current_level == 1 ? $faker->numberBetween(0, 1) : 0;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'age' => $faker->numberBetween(15, 55),
        'marketer_id' => $ad->creator_id,
        'marketer_name' => $ad->creator_name,
        "ad_id" => $ad->id,
        "ad_name" => $ad->name,
        "source_id" => $ad->source_id,
        "source_name" => $ad->source_name,
        "team_id" => $ad->team_id,
        "team_name" => $ad->team_name,
        "campaign_id" => $ad->campaign_id,
        "campaign_name" => $ad->campaign_name,
        "subcampaign_id" => $ad->subcampaign_id,
        "subcampaign_name" => $ad->subcampaign_name,
        "landingpage_id" => $ad->landingpage_id,
        "landingpage_name" => $ad->landingpage_name,
        'current_level' => $current_level,
        'is_transferred' => $current_level > 0 ? 1 : 0,
        'is_valid' => $is_valid,
        'invalid_reason' => $is_valid == 1 ? $faker->randomElement(array('Fake info', 'Could not contact')) : "",
        'is_returned' => $is_returned,
        'returned_reason' => $is_valid == 1 ? $faker->randomElement(array('Fake phone number', 'Could not contact in 5 days')) : "",
        'line_id' => $faker->ean8,
        'l1_time' => $l1_time,
        'l2_time' => $l2_time,
        'l3_time' => $l3_time,
        'l4_time' => $l4_time,
        'l5_time' => $l5_time,
        'l6_time' => $l6_time,
        'l7_time' => $l7_time,
        'l8_time' => $l8_time,
        'registered_date' => $registered_date->format('Y-m-d H:i:s'),
        'revenue' => $current_level == 8 ? $faker->numberBetween(8, 32) . '000' : '',
        'sale_person' => $faker->userName . '@topicanative.asia',
        'call_history' => array(
            array(
                'time' => '10-23-2017 16:00:00',
                'old_level' => 1,
                'new_level' => 2,
                'comment' => 'less than 18',
                'status' => 'stop contacting',
                'audio' => 'http://www.amclassical.com/mp3/amclassical_jingle_bells.mp3'
            ),
            array(
                'time' => '10-23-2017 16:00:00',
                'old_level' => 2,
                'new_level' => 3,
                'comment' => 'less than 18',
                'status' => 'stop contacting',
                'audio' => 'http://www.amclassical.com/mp3/amclassical_jingle_bells.mp3'
            )
        )
    ];
});
