<?php

use Faker\Generator as Faker;

$factory->define(
    Parking\ParkingLot::class,
    function (Faker $faker) {
        return [
            'name'        => $faker->company,
            'hourly_fare' => $faker->randomFloat(1, 0.1, 3.5),
            'capacity'    => $faker->numberBetween(100, 350),
        ];
    }
);
