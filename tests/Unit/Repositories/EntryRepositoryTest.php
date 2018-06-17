<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Parking\Repositories\ParkingLotRepository;
use Parking\Service\FreeSpotsService;
use Parking\Service\Validators\ValidatorFactory;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Repositories\EntryRepository */
final class EntryRepositoryTest extends TestCase {
    use RefreshDatabase;

    const ENTRIES_COUNT = 10;

    /** @testdox Given a DB with 10 items when I ask for all the items then it give a collection of 10 Entry */
    public function testGetByParkingLotId() {
        factory(ParkingLot::class)->create();
        factory(Entry::class, static::ENTRIES_COUNT)->create();

        $repository = $this->getRepository();

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

        $repository = $this->getRepository();

        $requestedEntry = $repository->getById(1);

        $this->assertEquals($entries->first()->id, $requestedEntry->id);
    }

    /**
     * @testdox When I ask ask for a non-existing item then it throws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetByIdThrowException() {
        $repository = $this->getRepository();

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

        $repository = $this->getRepository();

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

        $repository = $this->getRepository();

        $repository->addToParkingLot(1);
    }

    /** @testdox Given a valid price it should update the entry with the price and payed_at timestamp */
    public function testUpdateFieldForPrice() {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        factory(Entry::class)->create([
            'exited_at' => null,
            'price' => null,
            'payed_at' => null,
        ]);

        $repository = $this->getRepository();

        $updatedEntry = $repository->updateFields(1, [
            'price' => '22.4'
        ]);

        $this->assertEquals(22.4, $updatedEntry->price);
        $this->assertNotNull($updatedEntry->payed_at);
        $this->assertInstanceOf(Carbon::class, $updatedEntry->payed_at);
    }

    /**
     * @testdox Given a valid price and an entry with a price it should not update the entity
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testNotUpdateForPrice() {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        factory(Entry::class)->create([
            'exited_at' => null,
            'price' => 3.22,
            'payed_at' => new Carbon(),
        ]);

        $repository = $this->getRepository();

        $repository->updateFields(1, [
            'price' => '22.4'
        ]);
    }

    /** @testdox Given a valid exit time it should update the entry with the exited_at timestamp */
    public function testUpdateFieldForExitedAt() {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        factory(Entry::class)->create([
            'exited_at' => null,
            'price' => 3.22,
            'payed_at' => new Carbon('-3 minutes'),
        ]);

        $repository = $this->getRepository();

        $exitedAt = new Carbon;
        $updatedEntry = $repository->updateFields(1, [
            'exited_at' => $exitedAt
        ]);

        $this->assertEquals($exitedAt->getTimestamp(), $updatedEntry->exited_at->getTimestamp());
    }

    /**
     * @testdox Given a valid exit time and an entry without payed_tim it should not update the entry
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException
     */
    public function testNotUpdateFieldForExitedAtIfNotPayed() {
        factory(ParkingLot::class)->create([
            'capacity' => 10
        ]);
        factory(Entry::class)->create([
            'exited_at' => null,
            'price' => null,
            'payed_at' => null,
        ]);

        $repository = $this->getRepository();

        $exitedAt = new Carbon;
        $updatedEntry = $repository->updateFields(1, [
            'exited_at' => $exitedAt
        ]);

        $this->assertNull($exitedAt, $updatedEntry->exited_at);
    }

    private function getRepository(): EntryRepository {
        return new EntryRepository(
            new ParkingLotRepository,
            new FreeSpotsService,
            new ValidatorFactory
        );
    }

    /** @testdox Given a price to update when updateFields is called then the entry is updated */
    public function testUpdateFieldsPay() {
        factory(ParkingLot::class)->create([
            'capacity' => 10
        ]);
        factory(Entry::class)->create([
            'exited_at' => null,
            'price' => null,
            'payed_at' => null,
        ]);

        $data = [
            'price' => 3.22
        ];

        $repository = $this->getRepository();

        $entry = $repository->updateFields(1, $data);

        $this->assertEquals($data['price'], $entry->getAttribute('price'));
        $this->assertInstanceOf(Carbon::class, $entry->getAttribute('payed_at'));
    }

    /**
     * @testdox Given a non existing ID when updateFields is called then the correct exception is thrown
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testNotFound() {
        factory(ParkingLot::class)->create([
            'capacity' => 10
        ]);
        $repository = $this->getRepository();

        $repository->updateFields(1, []);
    }
}