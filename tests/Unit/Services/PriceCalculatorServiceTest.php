<?php
declare(strict_types=1);

namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Service\PriceCalculatorService;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Service\PriceCalculatorService */
final class PriceCalculatorServiceTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given a starting date an a fare when I ask for the price then it returns the correct one
     * @dataProvider provideDataForPriceCalculation
     */
    public function testReturnsExpectedPrice(Carbon $arrivedAt, float $hourlyFare, float $expectedPrice) {
        $price = (new PriceCalculatorService)->calculate($arrivedAt, $hourlyFare);

        $this->assertEquals($expectedPrice, $price);
    }

    public function provideDataForPriceCalculation(): array {
        return [
            [new Carbon('-15 minutes'), 1.00, 0.25],
            [new Carbon('-60 minutes'), 1.00, 1.00],
            [new Carbon('-70 minutes'), 1.00, 1.17],
        ];
    }

    /**
     * @testdox Given a valid entry when I ask for the price then it returns the correct one
     */
    public function testReturnsExpectedEntryPrice() {
        factory(ParkingLot::class)->create([
            'hourly_fare' => 1.00
        ]);
        /** @var Entry $entry */
        $entry = factory(Entry::class)->create([
            'price' => null,
            'payed_at' => null,
            'exited_at' => null,
            'arrived_at' => new Carbon('-30 minutes')
        ]);

        $price = (new PriceCalculatorService)->calculateForEntry($entry);

        $this->assertEquals(0.5, $price);
    }

    /**
     * @testdox Given an entry with a price when I calculate then it throws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testNotModifiedException() {
        factory(ParkingLot::class)->create();
        $entry = factory(Entry::class)->create([
            'price' => 33.2
        ]);

        (new PriceCalculatorService)->calculateForEntry($entry);
    }

    /**
     * @testdox Given an invalida arrival date when I calculate the price then it thows the correct exception
     * @dataProvider provideInvalidDates
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage Arrive time is not valid
     */
    public function testThrowsExceptionArrivedAt($invalidDate) {
        factory(ParkingLot::class)->create();
        /** @var Entry $entry */
        $entry = factory(Entry::class)->create([
            'price' => null
        ]);

        $entry->setAttribute('arrived_at', $invalidDate);

        (new PriceCalculatorService)->calculateForEntry($entry);
    }

    public function provideInvalidDates(): array {
        return [
            'null' => [null],
            'future date' => [new Carbon('+2 days')]
        ];
    }

    /**
     * @testdox Given an invalida arrival date when I calculate the price then it thows the correct exception
     * @dataProvider provideInvalidDates
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     * @expectedExceptionMessage This entry doesn't belong to any parking lot
     */
    public function testThrowsExceptionLot() {
        factory(ParkingLot::class)->create();
        /** @var Entry $entry */
        $entry = factory(Entry::class)->create([
            'price' => null
        ]);

        $entry->parking_lot_id = null;

        (new PriceCalculatorService)->calculateForEntry($entry);
    }
}