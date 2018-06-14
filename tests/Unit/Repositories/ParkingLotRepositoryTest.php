<?php

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Parking\ParkingLot;
use Parking\Repositories\ParkingLotRepository;
use Tests\TestCase;

final class ParkingLotRepositoryTest extends TestCase {
    use RefreshDatabase;

    const LOTS_COUNT = 3;

    /** @testdox Given a DB with 3 items when I ask for all the items then it give a collection of 3 ParkingLot */
    public function testAll() {
        factory(ParkingLot::class, static::LOTS_COUNT)->create();

        $repository = new ParkingLotRepository;

        $lots = $repository->getAll();

        $this->assertCount(static::LOTS_COUNT, $lots);

        $lots->each(function($lot) {
            $this->assertInstanceOf(ParkingLot::class, $lot);
        });
    }

    /** @testdox Give a DB with 3 items when I ask for the item with id 1 then it gives me the correct item */
    public function testGetById() {
        /** @var Collection $lots */
        $lots = factory(ParkingLot::class, static::LOTS_COUNT)->create();

        $repository = new ParkingLotRepository;

        $requestedLot = $repository->getById(1);

        $this->assertEquals($lots->first()->id, $requestedLot->id);
    }

    /**
     * @testdox Give a DB with 3 items when I ask for the item with id 1 then it gives me the correct item
     * @expectedException \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function testGetByIdThrowException() {
        $repository = new ParkingLotRepository;

        $repository->getById(1);
    }
}