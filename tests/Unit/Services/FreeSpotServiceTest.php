<?php

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Service\FreeSpotsService;
use Tests\TestCase;

final class FreeSpotServiceTest extends TestCase {
    use RefreshDatabase;

    /** @testdox Given free spots it should return true */
    public function testThereAreFreeSpots() {
        $lot = factory(ParkingLot::class)->create([
            'capacity' => 10
        ]);
        factory(Entry::class, 4)->create([
            'exited_at' => null
        ]);

        $freeSpotService = new FreeSpotsService;
        $this->assertTrue($freeSpotService->areThereFreeSpots($lot));
    }

    /** @testdox Given no free spots it should return false */
    public function testThereAreNoFreeSpots() {
        $lot = factory(ParkingLot::class)->create([
            'capacity' => 10
        ]);
        factory(Entry::class, 10)->create([
            'exited_at' => null
        ]);

        $freeSpotService = new FreeSpotsService;
        $this->assertFalse($freeSpotService->areThereFreeSpots($lot));
    }

}