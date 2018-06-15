<?php

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
}