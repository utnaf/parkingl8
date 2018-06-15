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

        // is the car in the garage?
        if ($faker->boolean(20)) {
            $arrivedAt = $faker->dateTimeBetween('-1 days', 'now');
            if ($faker->boolean(10)) {
                $payedAt = $faker->dateTimeInInterval($arrivedAt, '+12 hours');
                $price   =
                    (new PriceCalculatorService)->calculate(
                        Carbon::createFromTimestamp($arrivedAt->getTimestamp()),
                        $currentLot->hourly_fare
                    );
            }
            else {
                $payedAt = null;
                $price   = null;
            }

            $exitedAt = null;
        }
        else {
            $arrivedAt = $faker->dateTimeBetween('-20 days', '-2 days');
            $payedAt   = $faker->dateTimeInInterval($arrivedAt, '+1 days');
            $exitedAt  = $faker->dateTimeInInterval($payedAt, '+8 minutes');
            $price     =
                (new PriceCalculatorService)->calculate(
                    Carbon::createFromTimestamp($arrivedAt->getTimestamp()),
                    $currentLot->hourly_fare
                );
        }

        return [
            'parking_lot_id' => $currentLot->id,
            'arrived_at'     => $arrivedAt,
            'payed_at'       => $payedAt,
            'exited_at'      => $exitedAt,
            'price'          => $price,
        ];
    }
);
