<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Repositories\IssueRepository;
use Parking\Service\Validators\IsPayedValidator;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Service\Validators\IsPayedValidator */
final class PayedValidatorTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given payed_at null it should return false
     * @dataProvider provideNotValidPayedExitedDates
     */
    public function testPayingValidator($payedAt, $exitedAt) {
        factory(ParkingLot::class, 1)->create();
        $entry = factory(Entry::class)->create(
            [
                'payed_at' => null,
            ]
        );

        $validator = new IsPayedValidator(new IssueRepository);
        $this->assertFalse(
            $validator
                ->forEntry($entry)
                ->pass(
                    [
                        'exited_at' => new Carbon(),
                    ]
                )
        );
    }

    public function provideNotValidPayedExitedDates(): array {
        return [
            'payed is not null'  => [new Carbon(null), null],
            'exited is not null' => [null, new Carbon(null)],
            'both are not null'  => [new Carbon(null), new Carbon(null)],
        ];
    }

}