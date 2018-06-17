<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Parking\ParkingLot;
use Parking\Repositories\ParkingLotRepository;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Repositories\ParkingLotRepository */
final class ParkingLotRepositoryTest extends TestCase {
    use RefreshDatabase;

    /** @testdox Given a DB with 3 items when I ask for all the items then it give a collection of 3 ParkingLot */
    public function testAll() {
        factory(ParkingLot::class, 3)->create();

        $repository = new ParkingLotRepository;

        $lots = $repository->getAll();

        $this->assertCount(3, $lots);

        $lots->each(function($lot) {
            $this->assertInstanceOf(ParkingLot::class, $lot);
        });
    }

    /** @testdox Given a DB with 3 items when I ask for the item with id 1 then it gives me the correct item */
    public function testGetById() {
        /** @var Collection $lots */
        $lots = factory(ParkingLot::class, 3)->create();

        $repository = new ParkingLotRepository;

        $requestedLot = $repository->getById(1);

        $this->assertEquals($lots->first()->id, $requestedLot->id);
    }

    /**
     * @testdox When I ask ask for a non-existing item then it throws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetByIdThrowException() {
        $repository = new ParkingLotRepository;

        $repository->getById(1);
    }

    /** @testdox Given a lot and new data array when update is called then the lot is updated */
    public function testUpdate() {
        $parkingLot = factory(ParkingLot::class)->create([
            'name' => 'Name 1',
            'hourly_fare' => 3,
            'capacity' => 100,
            'threshold_minutes' => 10
        ]);

        $data = [
            'name' => 'Name 2',
            'hourly_fare' => 5,
            'capacity' => 50,
            'threshold_minutes' => 15
        ];

        $repository = new ParkingLotRepository;
        $updatedParkingLot = $repository->update($parkingLot, $data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $updatedParkingLot->getAttribute($key));
        }
    }
}