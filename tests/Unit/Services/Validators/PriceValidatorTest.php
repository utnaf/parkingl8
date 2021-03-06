<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Repositories\IssueRepository;
use Parking\Service\Validators\PriceValidator;
use Tests\TestCase;

/** @coversDefaultClass  \Parking\Service\Validators\PriceValidator */
final class PriceValidatorTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given a price it should return if it's correct or wrong in the right way
     * @dataProvider providePriceAndResult
     */
    public function testPriceValidation($price, bool $expectedBeahaviour) {
        factory(ParkingLot::class, 1)->create();
        $entry = factory(Entry::class)->create([
            'exited_at' => null,
            'price' => null,
            'payed_at' => null,
        ]);

        $validator = new PriceValidator(new IssueRepository);
        $this->assertEquals($expectedBeahaviour, $validator->forEntry($entry)->pass(['price' => $price]));
    }

    public function providePriceAndResult() {
        return [
            'correct price 1' => ['43', true],
            'correct price 2' => ['3.33', true],
            'correct price 3' => ['0.11', true],
            'correct price 4' => ['0', true],
            'wrong price 1' => ['-0.11', false],
            'wrong price 2' => ["asd", false],
            'wrong price 3' => ["12asd", false],
        ];
    }
}