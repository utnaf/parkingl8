<?php

use Faker\Generator as Faker;
use Parking\ParkingLot;

$parkingLots = [];
$factory->define(
    Parking\Entry::class,
    function (Faker $faker) use ($parkingLots) {
        if (empty($parkingLots)) {
            $parkingLots = ParkingLot::all();
        }
        /** @var ParkingLot $currentLot */
        $currentLot = $parkingLots->random();

        $arrivedAt = $faker->dateTimeBetween('-10 hours', '-10 minutes');

        return [
            'parking_lot_id' => $currentLot->id,
            'arrived_at'     => $arrivedAt,
            'payed_at'       => null,
            'exited_at'      => null,
            'price'          => null,
        ];
    }
);
