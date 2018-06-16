<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Service\Validators\PriceValidator;
use Tests\TestCase;

/** @coversClass \Parking\Service\Validators\PriceValidator */
final class PriceValidatorTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given a price it should return if it's correct or wrong in the right way
     * @dataProvider providePriceAndResult
     */
    public function testPriceValidation($price, bool $expectedBeahaviour) {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        $entry = factory(Entry::class)->create([
            'exited_at' => null,
            'price' => null,
            'payed_at' => null,
        ]);

        $validator = new PriceValidator;
        $this->assertEquals($expectedBeahaviour, $validator->forEntry($entry)->pass(['price' => $price]));
    }

    public function providePriceAndResult() {
        return [
            'correct price 1' => ['43', true],
            'correct price 2' => ['3.33', true],
            'correct price 3' => ['0.11', true],
            'wrong price 1' => ['-0.11', false],
            'wrong price 2' => ["asd", false],
            'wrong price 3' => ["12asd", false],
        ];
    }
}