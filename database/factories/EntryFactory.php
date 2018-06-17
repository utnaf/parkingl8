<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
use Parking\ParkingLot;
use Parking\Service\PriceCalculatorService;

$parkingLots = [];
$factory->define(
    Parking\Entry::class,
    function (Faker $faker) use ($parkingLots) {
        if (empty($parkingLots)) {
            $parkingLots = ParkingLot::all();
        }
        /** @var ParkingLot $currentLot */
        $currentLot = $parkingLots->random();

        $arrivedAt = $faker->dateTimeBetween('-24 hours', '-10 minutes');

        return [
            'parking_lot_id' => $currentLot->id,
            'arrived_at'     => $arrivedAt,
            'payed_at'       => null,
            'exited_at'      => null,
            'price'          => null,
        ];
    }
);
