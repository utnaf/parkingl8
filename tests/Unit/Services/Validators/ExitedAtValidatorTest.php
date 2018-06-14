<?php
declare(strict_types=1);

namespace Tests\Unit\Services\Validators;

use Illuminate\Support\Carbon;
use Parking\Service\Validators\ExitedAtValidator;

final class ExitedAtValidatorTest {

    /**
     * @testdox Given a date it should return if it's correct or wrong in the right way
     * @dataProvider provideDateAndResult
     */
    public function testExitedAtValidator($date, bool $expectedBeahaviour) {
        $validator = new ExitedAtValidator;
        $this->assertEquals($expectedBeahaviour, $validator->pass(['exited_at' => $date]));
    }

    public function provideDateAndResult() {
        return [
            'correct date 1' => [strtotime('-2 minutes'), true],
            'wrong date 1' => [time(), false],
            'wrong date 2' => [strtotime('+2 minutes'), true],
        ];
    }
}