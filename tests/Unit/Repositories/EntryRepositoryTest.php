<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Parking\Repositories\ParkingLotRepository;
use Parking\Service\FreeSpotsService;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Repositories\EntryRepository */
final class EntryRepositoryTest extends TestCase {
    use RefreshDatabase;

    const ENTRIES_COUNT = 10;

    /** @testdox Given a DB with 10 items when I ask for all the items then it give a collection of 10 Entry */
    public function testGetByParkingLotId() {
        factory(ParkingLot::class)->create();
        factory(Entry::class, static::ENTRIES_COUNT)->create();

        $repository = new EntryRepository(new ParkingLotRepository, new FreeSpotsService);

        $entries = $repository->getByParkingLotId(1);

        $this->assertCount(static::ENTRIES_COUNT, $entries);

        $entries->each(
            function ($entry) {
                $this->assertInstanceOf(Entry::class, $entry);
            }
        );
    }

    /** @testdox Given a DB with 10 items when I ask for the item with id 1 then it gives me the correct item */
    public function testGetById() {
        factory(ParkingLot::class)->create();
        /** @var Collection $entries */
        $entries = factory(Entry::class, static::ENTRIES_COUNT)->create();

        $repository = new EntryRepository(new ParkingLotRepository, new FreeSpotsService);

        $requestedEntry = $repository->getById(1);

        $this->assertEquals($entries->first()->id, $requestedEntry->id);
    }

    /**
     * @testdox When I ask ask for a non-existing item then it throws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetByIdThrowException() {
        $repository = new EntryRepository(new ParkingLotRepository, new FreeSpotsService);

        $repository->getById(1);
    }

    /** @testdox Given free space in the lot when I try to insert a new entry then it returns the new entry */
    public function testInsertNewEntry() {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        factory(Entry::class, 4)->create([
            'exited_at' => null
        ]);

        $repository = new EntryRepository(new ParkingLotRepository, new FreeSpotsService);

        $entry = $repository->addToParkingLot(1);
        $this->assertInstanceOf(Entry::class, $entry);
        $this->assertEquals(5, $entry->id);
    }


    /**
     * @testdox Given no free space in the lot when I try to insert a new entry then it throws the correct exception
     * @expectedException  \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
     */
    public function testNotInsertNewEntry() {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        factory(Entry::class, 10)->create([
            'exited_at' => null
        ]);

        $repository = new EntryRepository(new ParkingLotRepository, new FreeSpotsService);

        $repository->addToParkingLot(1);
    }
}