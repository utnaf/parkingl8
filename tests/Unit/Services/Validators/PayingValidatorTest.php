<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Service\Validators\PayingValidator;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Service\Validators\PayingValidator */
final class PayingValidatorTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given payed_at or exited_at not null it should thow and exception
     * @dataProvider provideNotValidPayedExitedDates
     */
    public function testPayingValidator($payedAt, $exitedAt) {
        factory(ParkingLot::class, 1)->create();
        $entry = factory(Entry::class)->create(
            [
                'arrived_at' => new Carbon('-30 minutes', config('app.timezone')),
                'exited_at'  => $exitedAt,
                'payed_at'   => $payedAt,
            ]
        );

        $validator = new PayingValidator;
        $this->assertFalse(
            $validator
                ->forEntry($entry)
                ->pass(
                    [
                        'price' => 2.88,
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