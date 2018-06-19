<?php

use Faker\Generator as Faker;
use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;

$entries = Entry::all();
$lots = ParkingLot::all();

$factory->define(
    Issue::class,
    function(Faker $faker) use ($entries, $lots) {
        $data = [];
        if(!$lots->isEmpty())  {
            $lot = $lots->random();
            $data['parking_lot_id'] = $lot->id;
        }
        elseif(!$entries->isEmpty()) {
            $entry = $entries->random();
            $data['entry_id'] = $entry->id;
        }

        $data['type'] = $faker->randomElement([
            Issue::TYPE_NOT_PAYED,
            Issue::TYPE_FULL
        ]);

        return $data;
    }
);