<?php

use Faker\Generator as Faker;
use Illuminate\Support\Carbon;
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

        // is the car in the garage?
        if ($faker->boolean(20)) {
            $arrivedAt = $faker->dateTimeBetween('-1 days', 'now');
            $payedAt   = null;
            $exitedAt  = null;
            $price     = null;
        }
        else {
            $arrivedAt  = $faker->dateTimeBetween('-20 days', '-2 days');
            $payedAt    = $faker->dateTimeInInterval($arrivedAt, '+1 days');
            $exitedAt   = $faker->dateTimeInInterval($payedAt, '+8 minutes');
            $hoursSpent = Carbon::createFromTimestamp($exitedAt->getTimestamp())->diffInHours();
            $price      = $hoursSpent * $currentLot->getAttribute('hourly_fare');
        }

        return [
            'parking_lot_id' => $currentLot->getKey(),
            'arrived_at'     => $arrivedAt,
            'payed_at'       => $payedAt,
            'exited_at'      => $exitedAt,
            'price'          => $price,
        ];
    }
);
