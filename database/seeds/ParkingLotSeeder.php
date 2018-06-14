<?php

use Illuminate\Database\Seeder;
use Parking\ParkingLot;

class ParkingLotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ParkingLot::class, 4)->create();
    }
}
