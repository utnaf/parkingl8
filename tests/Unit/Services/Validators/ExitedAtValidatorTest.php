<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Service\Validators\ExitedAtValidator;
use Tests\TestCase;

final class ExitedAtValidatorTest extends TestCase {
    use RefreshDatabase;

    /**
     * @testdox Given a date it should return if it's correct or wrong in the right way
     * @dataProvider provideDateAndResult
     */
    public function testExitedAtValidator(string $dateString, bool $expectedBeahaviour) {
        factory(ParkingLot::class, 1)->create([
            'capacity' => 10
        ]);
        $entry = factory(Entry::class)->create([
            'arrived_at' => new Carbon('-30 minutes', config('app.timezone')),
            'exited_at' => null,
            'price' => 2.30,
            'payed_at' => new Carbon('-10 minutes', config('app.timezone')),
        ]);

        $validator = new ExitedAtValidator;
        $this->assertEquals(
            $expectedBeahaviour,
            $validator
                ->forEntry($entry)
                ->pass([
                    'exited_at' => new Carbon($dateString, config('app.timezone'))
                ])
        );
    }

    public function provideDateAndResult() {
        return [
            '-2 minutes' => ['-2 minutes', true],
            'now' => ['', true],
            '+2 minutes' => ['+2 minutes', true],
            '+10 minutes (same as thershold)' => ['+10 minutes', true],
            '+11 minutes (over the threshold)' => ['+11 minutes', false],
        ];
    }
}